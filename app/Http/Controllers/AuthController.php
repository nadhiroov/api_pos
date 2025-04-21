<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Branch;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (User::where('username', $data['username'])->count() == 1) {
            throw new HttpResponseException(response([
                "message" => "validation errors",
                "errors" => [
                    "username" => [
                        "username already registered"
                    ]
                ]
            ], 400));
        }
        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();
        return response()->json([
            "message" => "User created successfully",
            "data"    => [
                "name"  => $data['name'],
                "username"  => $data['username'],
                "email"  => $data['email'] ?? '-',
            ]
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (!Auth::attempt($data)) {
            return response()->json([
                "message" => "Username or password incorrect"
            ], 401);
        }
        $dataUser = User::where('username', $data['username'])->first();
        $role = Role::join("user_role as ur", "ur.role_id", "=", "roles.id")
            ->join("users as u", "u.id", "=", "ur.user_id")
            ->where("user_id", $dataUser->id)
            ->pluck("roles.role_name")->toArray();
        if (empty($role)) {
            $role = ["cashier"];
        }
        $rolesCollect = collect($role);
        if ($rolesCollect->contains("owner")) {
            // $branches = Shop::query()->with('branches')->where('user_id', $dataUser->id)->get();
            // $branches = Branch::query()->
            // $branches = Branch::whereIn('shop_id', Shop::where('user_id', $dataUser->id)->pluck('id'))->get();
            $branches = Branch::whereIn('shop_id', Shop::where('user_id', $dataUser->id)->pluck('id'))
                ->select('id', 'name')->get();
        } elseif ($rolesCollect->contains("cashier")) {
            $branches = Branch::whereJsonContains('user_id', $dataUser->id)->get();
        }

        $token = $dataUser->createToken('api', $role);
        return response()->json([
            "message"   => "logged in",
            "data"      => [
                "id"        => $dataUser->id,
                "username"  => $dataUser->username,
                "email"     => $dataUser->email ?? '-',
                "role"      => $role,
                "branches"  => $branches ?? '-',
                "token"     => $token->plainTextToken
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
