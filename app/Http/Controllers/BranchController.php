<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index() {
        
    }

    function products(Request $request) {
        $user = Auth::user();
        $branch_id = $request->input('branch_id');
        // $data = Branch::with('products')->where('')
        $data = Product::with('branch')->where('');
    }
}
