<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Product';
    }
    public function index()
    {
        return view('product.index', [
            'title' => $this->title,
        ]);
    }

    public function add()
    {
        $user = Auth::user();
        $branches = Branch::with('shop')->whereHas('shop', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        $categories = Category::with('shop')->whereHas('shop', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        return view('product.add', [
            'title' => 'Add Product',
            'branches' => $branches,
            'categories' => $categories,
        ]);
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $products = Product::with(['branch.shop']) // Eager loading relasi nested
            ->select(['products.*']); // Select semua kolom dari products
        if ($request['branch_id'] != null) {
            $products->where('branch_id', $request['branch_id']);
        }else{
            $products->whereHas('branch.shop', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return DataTables::of($products)
            ->addIndexColumn()
            ->addColumn('shop_name', function ($product) {
                return $product->branch->shop->name ?? '-';
            })
            ->addColumn('branch_name', function ($product) {
                return $product->branch->name ?? '-';
            })
            ->addColumn('price_formatted', function ($product) {
                return 'Rp ' . number_format($product->price, 0, ',', '.');
            })
            ->addColumn('action', function ($product) {
                return '<div class="d-flex align-items-center gap-2">
                <a href="' . route('product.show', $product->id) . '" class="btn bg-info-subtle text-info"><i class="ti ti-zoom-exclamation fs-4 me-2"></i></a>
                <a href="' . route('product.show', $product->id) . '" class="btn bg-warning-subtle text-warning"><i class="ti ti-edit fs-4 me-2"></i></a>
                <button class="btn bg-danger-subtle text-danger" data-id="' . $product->id . '"><i class="ti ti-trash fs-4 me-2"></i></button>
                    </div>';
            })
            ->addColumn('image', function ($product) {
                return '<div clas="flex-shrink-0"><img src="' . $product->image . '" 
                class="rounded img-fluid" 
                    onerror="this.onerror=null;this.src=\'' . asset('assets/images/products/empty-shopping-bag.gif') . '\'" 
                    alt="Product Image" width="70" height="70"></div>';
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    public function store(Request $request)
    {
        // validasi input
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'branch_id'   => 'required|exists:branches,id',
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:30|unique:products,sku',
            'unit'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'barcode'     => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // proses upload image jika ada
        if ($request->hasFile('image')) {
            // menyimpan di storage/app/public/products
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product = Product::create($validated);

        return response()->json([
            'status'  => 'Success',
            'message' => 'Data saved'
        ]);
    }
}
