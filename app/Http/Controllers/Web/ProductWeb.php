<?php

namespace App\Http\Controllers\Web;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

    public function add($id = '')
    {
        if ($id != '') {
            $branch = Branch::where('id', $id)->first();
        }
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
            'dataBranch' => $branch ?? null,
        ]);
    }

    public function edit($id = '') {
        $data = Product::with(['branch.shop'])->where('id', $id)->first();
        $user = Auth::user();
        $branches = Branch::with('shop')->whereHas('shop', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        $categories = Category::with('shop')->whereHas('shop', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
        return view('product.edit', [
            'title' => 'Edit Product',
            'data' => $data,
            'categories' => $categories,
            'branches' => $branches,
            'dataBranch' => $branch ?? null,
        ]);
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $products = Product::with(['branch.shop']) // Eager loading relasi nested
            ->select(['products.*']); // Select semua kolom dari products
        if ($request['branch_id'] != null) {
            $products->where('branch_id', $request['branch_id']);
        } else {
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
            ->addColumn('stock', function ($p) {
                $stock = $p->stock;
                if ($stock === null) {
                    return '-';
                }
                if ($stock <= 10) {
                    $cls = 'danger';
                } elseif ($stock <= 20) {
                    $cls = 'warning';
                } else {
                    $cls = 'success';
                }
                return '<span class="badge text-bg-' . $cls . ' fs-1">' . $stock . '</span>';
            })
            ->addColumn('price_formatted', function ($product) {
                return 'Rp ' . number_format($product->price, 0, ',', '.');
            })
            ->addColumn('action', function ($product) {
                return '<div class="d-flex align-items-center gap-2">
                <a href="' . route('product.show', $product->id) . '" class="btn bg-info-subtle text-info"><i class="ti ti-zoom-exclamation fs-4 me-2"></i></a>
                <a href="' . route('product.edit', $product->id) . '" class="btn bg-warning-subtle text-warning"><i class="ti ti-edit fs-4 me-2"></i></a>
                <a onclick="confirmDelete(this)" class="btn bg-danger-subtle text-danger" target="product" data-id="' . $product->id . '"><i class="ti ti-trash fs-4 me-2"></i></a>
                    </div>';
            })
            ->addColumn('image', function ($product) {
                $url = route('product.image', $product->image);
                return '<div clas="flex-shrink-0"><img src="' . $url . '" 
                class="rounded img-fluid" 
                    onerror="this.onerror=null;this.src=\'' . asset('assets/images/products/empty-shopping-bag.gif') . '\'" 
                    alt="Product Image" width="70" height="70"></div>';
            })
            ->rawColumns(['action', 'image', 'stock'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'branch_id'   => 'required|array',
            'branch_id.*' => 'exists:branches,id',
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|max:30|unique:products,sku',
            'unit'        => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'nullable|integer|min:0',
            'barcode'     => 'nullable|string',
            'image'       => 'required|string', // nama file di temp
        ]);

        $tempFilename = $validated['image'];

        if (Storage::disk('public')->exists("temp/{$tempFilename}")) {
            Storage::disk('public')->move("temp/{$tempFilename}", "products/{$tempFilename}");
        } else {
            return response()->json([
                'status'  => 'Error',
                'message' => "Temporary file {$tempFilename} not found."
            ], 404);
        }

        $baseData = Arr::only($validated, [
            'category_id',
            'name',
            'sku',
            'unit',
            'description',
            'price',
            'stock',
            'barcode'
        ]);
        $baseData['image'] = $request['image'];
        $created = [];
        DB::beginTransaction();
        try {
            foreach ($validated['branch_id'] as $branchId) {
                $data             = $baseData;
                $data['branch_id'] = $branchId;
                $created[]        = Product::create($data);
            }
            DB::commit();
            return response()->json([
                'status'   => 'Success',
                'message'  => 'Data saved.'
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(int $id, ProductRequest $request) {
        $validated = $request->validated();
        $product   = Product::find($id);
        if (!$product) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Data not found'
            ]);
        }
        $baseData = Arr::only($validated, [
            'category_id',
            'name',
            'sku',
            'unit',
            'description',
            'price',
            'stock',
            'barcode'
        ]);
        if ($request['image'] != null) {
            $tempFilename = $request['image'];
            if (Storage::disk('public')->exists("temp/{$tempFilename}")) {
                Storage::disk('public')->move("temp/{$tempFilename}", "products/{$tempFilename}");
                $baseData['image'] = $request['image'];
            } else {
                return response()->json([
                    'status'  => 'Error',
                    'message' => "Temporary file {$tempFilename} not found."
                ], 404);
            }
        }
        DB::beginTransaction();
        try {
            $product->update($baseData);
            DB::commit();
            return response()->json([
                'status'  => 'Success',
                'message' => 'Data saved.'
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $file = $request->file('file');

        $uuid     = Str::uuid()->toString();
        $ext      = $file->getClientOriginalExtension();
        $filename = $uuid . '.' . $ext;
        $file->storeAs('temp', $filename, 'public');
        return response()->json([
            'filename' => $filename,
        ], 200);
    }

    public function showImage(string $filename)
    {
        $path = "products/{$filename}";
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Image not found');
        }
        $file    = Storage::disk('public')->get($path);
        // $mime    = Storage::disk('public')->mimeType($path);
        return response($file, 200)
            // ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    public function destroy(int $id)
    {
        Auth::user();
        $product = Product::where('id', $id)->first();
        if (!$product) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Data not found'
            ]);
        }

        try {
            $product->delete();
            return response()->json([
                'status'  => 'Success',
                'message' => 'Data deleted'
            ]);
        } catch (\Exception $er) {
            return response()->json([
                'status'  => 'Error',
                'code'      => $er->getCode(),
                'message' => $er->getMessage()
            ]);
        }
    }
}
