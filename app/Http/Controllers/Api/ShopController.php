<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ShopCollection;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class ShopController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Shop::orderBy('name')->get();
        return response()->json([
            "message"   => "",
            "data"      => $data
        ]);
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $shops = Shop::query()->with('categories', 'branches', 'products')->where('user_id', $user->id);

        $shops = $shops->where(function (Builder $builder) use ($request) {
            $name = $request->input('name');
            if ($name) {
                $builder->where('name', 'like', '%' . $name . '%');
            }

            $phone = $request->input('phone');
            if ($phone) {
                $builder->where('phone', 'like', '%' . $phone . '%');
            }
        });

        $shops = $shops->paginate(perPage: $size, page: $page);
        return new ShopCollection($shops);
    }
}
