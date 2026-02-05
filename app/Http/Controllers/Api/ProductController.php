<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /* public function index(Request $request)
    {
        Auth::user();
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $branch_id = $request->input('branch_id');
        $product = Cache::remember('products-branch-'.$branch_id, 300, function () use ($branch_id) {
            return Product::query()->with('category', 'branch')->where('branch_id', $branch_id);
        });
        $products = $product->paginate(perPage: $size, page: $page);
        return new ProductCollection($products);
    } */

    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 10);
        $branch_id = $request->input('branch_id');

        $cacheKey = "products-branch-{$branch_id}";

        $products = Cache::remember($cacheKey, 300, function () use ($branch_id) {
            return Product::with('category', 'branch')
                ->where('branch_id', $branch_id)
                ->get();
        });

        $paginated = $products->forPage($page, $size);

        return new ProductCollection($paginated);
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
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
