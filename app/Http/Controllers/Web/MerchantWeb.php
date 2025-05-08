<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Delimiter\Bracket;
use Yajra\DataTables\Facades\DataTables;

class MerchantWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Merchant';
    }

    public function index()
    {
        return view('merchant.index', [
            'title' => $this->title,
        ]);
    }

    public function add()
    {
        return view('merchant.add');
    }

    function edit(int $id)
    {
        Auth::user();
        $branch = Branch::where('id', $id)->first();
        return view('merchant.edit', ['data' => $branch]);
    }

    function detail($id)
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
            ->with('shop');
        return DataTables::of($branches)
            ->addColumn('action', function ($branch) {
                $btn = '<div class="d-flex align-items-center gap-2">';
                $btn .= '<a href="' . route('merchant.detail', $branch->id) . '" class="btn bg-info-subtle text-info"><i class="ti ti-zoom-exclamation fs-4 me-2"></i></a>';
                $btn .= '<button data-bs-toggle="modal" data-bs-target="#edit" data-id="' . $branch->id . '" class="btn bg-warning-subtle text-warning"><i class="ti ti-edit fs-4 me-2"></i></button>';
                $btn .= '<a onclick="confirmDelete(this)" class="btn bg-danger-subtle text-danger" data-id="' . $branch->id . '"><i class="ti ti-trash fs-4 me-2"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            // <a href="' . route('product.show', $product->id) . '" class="btn bg-info-subtle text-info"><i class="ti ti-zoom-exclamation fs-4 me-2"></i></a>
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

    public function store(Request $request)
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

    public function update(int $id, Request $request)
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
        $branch = new Branch($saveData);
        $branch->where('id', $id)->update($saveData);
        return response()->json([
            'status'  => 'Success',
            'message' => 'Data saved'
        ]);
    }

    public function destroy(int $id)
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
