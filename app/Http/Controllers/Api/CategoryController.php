<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class CategoryController extends Controller
{
    function index(Request $request) {
        Auth::user();
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        if (!isset($request['shop_id']) || $request['shop_id'] == null) {
            return response()->json([
                "message" => 'shop_id is required',
            ], 400);
        }
        $categories = Category::query()->select('id','name')->where('shop_id', $request['shop_id']);
        $categories = $categories->where(function (Builder $builder) use ($request) {
            $name = $request->input('name');
            if ($name) {
                $builder->where('name', 'like', '%' . $name . '%');
            }
        });

        $categories = $categories->paginate(perPage: $size, page: $page);
        return new CategoryCollection($categories);
    }
}
