<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Arr;
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
        if (isset($request['code']) && $request['code'] != null) {
            $user->id;
            $shop = Shop::where('code', $request['code'])->first();
            if ($shop) {
                $staffIds = $shop->staff_id ?? [];
                if (!in_array($user->id, $staffIds)) {
                    array_push($staffIds, $user->id);
                    Arr::sort($staffIds);
                }
                $shop->staff_id = $staffIds;
                $shop->save();
            }
        }
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
        // return response()->json([
        //     'message'   => $data
        // ]);
        if ($data['status']) {
            $token = $data['dataUser']->createToken("api", $data["dataUser"]["role"]);
            return response()->json([
                "message"   => "logged in",
                "data"      => [
                    "id"        => $data["dataUser"]["id"],
                    "username"  => $data["dataUser"]["username"],
                    "name"      => $data["dataUser"]["name"],
                    "email"     => $data["dataUser"]["email"] ?? '-',
                    "role"      => $data["dataUser"]["role"],
                    "shop"      => $data["dataUser"]["shop"] ?? '-',
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
}
