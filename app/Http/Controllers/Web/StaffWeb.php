<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Models\User;
use App\Http\Controllers\Controller;
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

    public function add()
    {
        return view('staff.add');
    }

    public function edit($id)
    {
        return view('staff.edit', [
            'title' => $this->title,
            // 'data'  => Staff::where('id', $id)->first()
        ]);
    }

    public function show() {
        $user = Auth::user();
        // 1. Ambil shop beserta semua branch-nya
        $shop = Shop::with('branches')->where('user_id', $user->id)->first();

        // ambil array staff_id dari JSON → bisa kosong
        $staffIds = $shop->staff_id ?? [];

        // query user yang ID-nya ada di staff_id
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
            ->addColumn('action', function ($staff) {
                return '<div class="d-flex align-items-center gap-2">
                <a href="' . route('product.edit', $staff->id) . '" class="btn bg-warning-subtle text-warning"><i class="ti ti-edit fs-4 me-2"></i></a>
                <a onclick="confirmDelete(this)" class="btn bg-danger-subtle text-danger" target="product" data-id="' . $staff->id . '"><i class="ti ti-trash fs-4 me-2"></i></a>
                    </div>';
            })
            ->rawColumns(['branches', 'action'])
            ->make(true);
    }
}
