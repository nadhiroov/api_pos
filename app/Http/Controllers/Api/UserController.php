<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() : JsonResponse {
        $data = User::orderBy('name')->get();
        return response()->json([
            "message"   => "",
            "data"      => $data
        ]);
    }
}
