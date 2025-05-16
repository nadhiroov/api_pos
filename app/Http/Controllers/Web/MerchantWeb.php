<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Delimiter\Bracket;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class MerchantWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Merchant';
    }

    public function index() : View
    {
        return view('merchant.index', [
            'title' => $this->title,
        ]);
    }

    public function add()
    {
        return view('merchant.add');
    }

    function edit(int $id) : View
    {
        Auth::user();
        $branch = Branch::where('id', $id)->first();
        return view('merchant.edit', ['data' => $branch]);
    }

    function detail($id) : View
    {
        Auth::user();
        $branch = Branch::where('id', $id)->first();
        return view('merchant.detail', [
            'title' => 'Detail merchant',
            'branch' => $branch,
            'data'  => Branch::where('id', $id)->first()
        ]);
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $branches = Branch::query()
            ->whereHas('shop', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('shop')
            ->withCount('products');
        return DataTables::of($branches)
            ->addColumn('product_count', fn($branch) => $branch->products_count)
            ->addColumn('action', function ($branch) {
                $btn = '<div class="d-flex align-items-center gap-2">';
                $btn .= '<a href="' . route('merchant.detail', $branch->id) . '" class="btn bg-info-subtle text-info"><i class="ti ti-zoom-exclamation fs-4 me-2"></i></a>';
                $btn .= '<button data-bs-toggle="modal" data-bs-target="#edit" data-id="' . $branch->id . '" class="btn bg-warning-subtle text-warning"><i class="ti ti-edit fs-4 me-2"></i></button>';
                $btn .= '<a onclick="confirmDelete(this)" class="btn bg-danger-subtle text-danger" data-id="' . $branch->id . '"><i class="ti ti-trash fs-4 me-2"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->filter(function ($query) use ($request) {
                // Handle search
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhereHas('shop', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }

                // Handle column-specific search
                if ($request->has('columns')) {
                    foreach ($request->columns as $column) {
                        if ($column['searchable'] == 'true' && !empty($column['search']['value'])) {
                            $searchValue = $column['search']['value'];
                            if ($column['name'] == 'shop.name') {
                                $query->whereHas('shop', function ($q) use ($searchValue) {
                                    $q->where('name', 'like', '%' . $searchValue . '%');
                                });
                            } else {
                                $query->where($column['name'], 'like', '%' . $searchValue . '%');
                            }
                        }
                    }
                }
            })
            ->order(function ($query) use ($request) {
                if ($request->has('order')) {
                    $order = $request->order[0];
                    $column = $request->columns[$order['column']]['data'];

                    // Handle ordering for related columns
                    if ($column == 'shop.name') {
                        $query->join('shops', 'branches.shop_id', '=', 'shop.id')
                            ->orderBy('shops.name', $order['dir'])
                            ->select('branches.*');
                    } else {
                        $query->orderBy($column, $order['dir']);
                    }
                }
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    function showStaff(Request $request, $branchId) {
        $branch = Branch::findOrFail($branchId);
        $staffIds = $branch->user_id ?? [];

        // 1) Query User + join ke user_role dan roles
        $query = User::whereIn('users.id', $staffIds)
            ->leftJoin('user_role', 'users.id', '=', 'user_role.user_id')
            ->leftJoin('roles',     'user_role.role_id', '=', 'roles.id')
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'roles.role_name as role_name'
            ]);

        // 2) Kirim ke DataTables
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('name',  fn($u) => $u->name)
            ->addColumn('role_name', fn($u) => $u->role_name ?? '-')
            ->addColumn('branches', function ($u) use ($branch) {
                // badge per branch
                $badges = collect($branch->branches)
                    ->filter(fn($b) => in_array($u->id, $b->user_id ?? []))
                    ->map(fn($b) => '<span class="badge bg-secondary me-1">' . e($b->name) . '</span>')
                    ->implode('');
                return $badges ?: '<span class="text-muted">—</span>';
            })
            ->addColumn('action', function ($user) {
                $btn = '<div class="d-flex align-items-center gap-2">';
                $btn .= '<a onclick="confirmDelete(this)" class="btn bg-danger-subtle text-danger" data-id="' . $user->id . '"><i class="ti ti-trash fs-4 me-2"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['branches', 'action'])
            ->make(true);
    }

    public function store(Request $request) : JsonResponse
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => ['required'],
            'address' => ['nullable'],
            'phone' => ['nullable'],
        ]);
        $shop = Shop::where('user_id', $user->id)->first();
        $saveData = [
            'shop_id'  => $shop->id,
            'name'     => $data['name'],
            'address'  => $data['address'] ?? null,
            'phone'    => $data['phone'] ?? null,
        ];
        $category = new Branch($saveData);
        $category->save();
        return response()->json([
            'status'  => 'Success',
            'message' => 'Data saved'
        ]);
    }

    public function update(int $id, Request $request) : JsonResponse
    {
        Auth::user();
        $data = $request->validate([
            'name' => ['required'],
            'address' => ['nullable'],
            'phone' => ['nullable'],
        ]);
        $saveData = [
            'name'     => $data['name'],
            'name'     => $data['name'],
            'address'  => $data['address'] ?? null,
            'phone'    => $data['phone'] ?? null,
        ];
        $updated = Branch::where('id', $id)->update($saveData);
        if ($updated) {
            return response()->json([
                'status'  => 'Success',
                'message' => 'Data saved'
            ], 200);
        } else {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Failed to update. Branch not found or no changes detected.'
            ], 400);
        }
    }

    public function destroy(int $id) : JsonResponse
    {
        Auth::user();
        $branch = Branch::where('id', $id)->first();
        if (!$branch) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Data not found'
            ]);
        }

        try {
            $branch->delete();
            return response()->json([
                'status'  => 'Success',
                'message' => 'Data deleted'
            ]);
        } catch (\Exception $er) {
            return response()->json([
                'status'  => 'Error',
                'code'      => $er->getCode(),
                'message' => $er->getMessage()
            ]);
        }
    }
}
