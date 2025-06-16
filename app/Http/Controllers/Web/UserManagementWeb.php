<?php

namespace App\Http\Controllers\Web;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class UserManagementWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'User Management';
        // if not Auth::user()->hasRole('admin'))
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function index()
    {
        return view('UserManagement.index', [
            'title' => $this->title,
        ]);
    }

    public function edit($id = '')
    {
        $user = User::with('roles')->findOrFail($id);
        $allRoles = Role::all();
        $userRoleIds = $user->roles->pluck('id')->toArray();
        return view('UserManagement.edit', compact(
            'user',
            'allRoles',
            'userRoleIds'
        ));
    }

    public function show()
    {
        $query = User::with('roles')
            ->select(['id', 'name', 'email']);

        return DataTables::of($query)
            ->addColumn('roles', function (User $user) {
                return $user->roles
                    ->map(
                        fn($role) =>
                        '<span class="badge bg-secondary me-1">'
                            . e($role->role_name) .
                            '</span>'
                    )
                    ->implode('');
            })
            ->addColumn('action', function (User $user) {
                return '<div class="d-flex align-items-center gap-2">
                <button data-bs-toggle="modal" data-bs-target="#edit" data-id="' . $user->id . '" class="btn bg-info-subtle text-info"><i class="ti ti-zoom-exclamation fs-4 me-2"></i></button>
                </div>';
            })
            ->rawColumns(['roles', 'action'])
            ->make(true);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'roles'   => 'array',
            'roles.*' => 'integer|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        try {
            $user->roles()->sync($data['roles'] ?? []);
            return response()->json([
                'status'  => 'Success',
                'message' => 'Roles updated successfully.'
            ]);
        } catch (\Exception $er) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Failed to update roles: ' . $er->getMessage(),
            ], 500);
        }
    }
}
