<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(array $credentials)
    {
        if (!Auth::attempt($credentials)) {
            return [
                "status" => false,
                "http" => 401,
                "message" => "Username or password incorrect"
            ];
        }
        $dataUser = User::where('username', $credentials['username'])->first();
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
                ->select('id', 'name', 'shop_id')->get();
        } elseif ($rolesCollect->contains("cashier")) {
            $branches = Branch::whereJsonContains('user_id', $dataUser->id)->select('id','name', 'shop_id')->get();
        }
        $dataUser["shop_id"] = $dataUser->shop_id;
        $dataUser["role"] = $role;
        $dataUser["branches"] = $branches;
        return [
            "status"        => true,
            "dataUser"      => $dataUser
        ];
    }
}
