<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

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
        $data = $this->authService->login($data);
        if ($data['status']) {
            $token = $data['dataUser']->createToken("api", $data["dataUser"]["role"]);
            return response()->json([
                "message"   => "logged in",
                "data"      => [
                    "id"        => $data["dataUser"]["id"],
                    "username"  => $data["dataUser"]["username"],
                    "email"     => $data["dataUser"]["email"] ?? '-',
                    "role"      => $data["dataUser"]["role"],
                    "branches"  => $data["dataUser"]["branches"] ?? '-',
                    "token"     => $token->plainTextToken
                ]
            ], 200);
        } else {
            return response()->json([
                'message'   => $data['message']
            ], $data['http']);
        }
    }

    /* if (!Auth::attempt($data)) {
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
            $branches = Branch::whereIn('shop_id', Shop::where('user_id', $dataUser->id)->pluck('id'))
                ->select('id', 'name')->get();
        } elseif ($rolesCollect->contains("cashier")) {
            $branches = Branch::whereJsonContains('user_id', $dataUser->id)->get();
        } */
}
