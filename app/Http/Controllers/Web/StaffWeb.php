<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StaffWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Staff Management';
    }

    public function index()
    {
        $user = Auth::user();
        // 1. Ambil shop beserta semua branch-nya
        return view('staff.index', [
            'title' => $this->title,
        ]);
    }

    public function add($branch_id = '')
    {
        $user = Auth::user();
        $shop = Shop::with('branches')->where('user_id', $user->id)->first();
        $branch = Branch::where('id', $branch_id)->first();
        $staffs = User::whereIn('id', $shop->staff_id)->get();
        // dd($branch->user_id);
        return view('staff.add', [
            'title' => "Add Staff",
            'data' => $staffs,
            'branch' => $branch,
            'branches' => $shop->branches,
        ]);
    }

    public function edit($id = '')
    {
        $user = Auth::user();
        // $shop = Shop::with('branches')->where('user_id', $user->id)->first();
        // echo $shop;die;
        $shop = Shop::where('user_id', $user->id)->first();
        $branches = Branch::where('shop_id', $shop->id)->get();
        return view('staff.edit', [
            'title'   => $this->title,
            'data'    => $branches,
            'user_id' => $id,
        ]);
    }

    public function show()
    {
        $user = Auth::user();
        $shop = Shop::with('branches')->where('user_id', $user->id)->first();
        $staffIds = $shop->staff_id ?? [];
        $staffs = User::whereIn('id', $staffIds)->get();
        return DataTables::of($staffs)
            ->addIndexColumn()
            ->addColumn('name', fn(User $u) => $u->name)
            ->addColumn('email', fn(User $u) => $u->email)
            ->addColumn('branches', function (User $u) use ($shop) {
                $badges = $shop->branches
                    ->filter(fn($b) => in_array($u->id, $b->user_id ?? []))
                    ->map(fn($b) => '<span class="badge text-bg-success">' . e($b->name) . '</span>')
                    ->implode(' ');

                return $badges ?: '<span class="text-muted">—</span>';
            })
            ->rawColumns(['branches'])
            ->make(true);
    }

    public function editBranch(Request $request)
    {
        $user = Auth::user();
        dd($request['branches_id']);
        $shop = Shop::with('branches')->where('user_id', $user->id)->first();
        foreach ($shop->branches as $branch) {
            # code...
        }
        $staffIds = $shop->staff_id ?? [];
        $staffIds[] = $request->id;
        $shop->update(['staff_id' => $staffIds]);
        return redirect()->route('staff.index')->with('success', 'Staff added successfully');
    }

    public function store(Request $request) {
        $branch = Branch::findOrFail($request->branch_id);
        $saveData['user_id'] = $request->user_id;
        $saveData['user_id'] = array_map('intval', $saveData['user_id']);
        $updated = Branch::where('id', $branch->id)->update([
            'user_id' => $saveData['user_id']
        ]);
        if ($updated) {
            return response()->json([
                'status'  => 'Success',
                'message' => 'Data saved'
            ], 200);
        } else {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Failed to update data.'
            ], 400);
        }
    }
}
