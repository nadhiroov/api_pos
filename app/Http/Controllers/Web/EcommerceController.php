<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EcommerceController extends Controller
{
    public function index() {
        $branches = Branch::get();
        return view('ecommerce.index',[
            'branches' => $branches
        ]);
    }

    public function getCategory(Request $request) {
        $categories = Category::where('branch_id', $request->branchId)->get();
        $products = Product::where('branch_id', $request->branchId)->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                'image_url' => route('product.image', ['filename' => $product->image])
            ];
        });
        return response()->json([
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
