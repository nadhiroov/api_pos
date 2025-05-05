<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CategoryWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Category';
    }
    public function index()
    {
        return view('category.index', [
            'title' => $this->title,
            // 'datas' => $this->getData()
        ]);
    }

    public function show(Request $request)
    {
        $user = Auth::user();

        $categories = Category::query()
            ->whereHas('shop', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with('shop');

        return DataTables::of($categories)
            // ->addIndexColumn()
            ->addColumn('action', function ($category) {
                $btn = '<div class="d-flex align-items-center gap-2">';
                // $btn .= '<a href="' . route('category.edit', $category->id) . '" class="btn bg-warning-subtle text-warning"><i class="ti ti-edit fs-4 me-2"></i></a>';
                $btn .= '<button data-bs-toggle="modal" data-bs-target="#edit" data-id="' . $category->id . '" class="btn bg-warning-subtle text-warning"><i class="ti ti-edit fs-4 me-2"></i></button>';
                $btn .= '<a onclick="confirmDelete(this)" class="btn bg-danger-subtle text-danger" data-id="' . $category->id . '"><i class="ti ti-trash fs-4 me-2"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->addColumn('name', function ($category) {
                return $category->name;
            })
            ->filter(function ($query) use ($request) {
                // Handle search
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhereHas('shop', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }

                // Handle column-specific search
                if ($request->has('columns')) {
                    foreach ($request->columns as $column) {
                        if ($column['searchable'] == 'true' && !empty($column['search']['value'])) {
                            $searchValue = $column['search']['value'];
                            if ($column['name'] == 'shop.name') {
                                $query->whereHas('shop', function ($q) use ($searchValue) {
                                    $q->where('name', 'like', '%' . $searchValue . '%');
                                });
                            } else {
                                $query->where($column['name'], 'like', '%' . $searchValue . '%');
                            }
                        }
                    }
                }
            })
            ->order(function ($query) use ($request) {
                if ($request->has('order')) {
                    $order = $request->order[0];
                    $column = $request->columns[$order['column']]['data'];

                    // Handle ordering for related columns
                    if ($column == 'shop.name') {
                        $query->join('shops', 'categories.shop_id', '=', 'shops.id')
                            ->orderBy('shops.name', $order['dir'])
                            ->select('categories.*');
                    } else {
                        $query->orderBy($column, $order['dir']);
                    }
                }
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function add()
    {
        return view('category.add');
    }

    function edit(int $id)
    {
        Auth::user();
        $category = Category::where('id', $id)->first();
        return view('category.edit', ['data' => $category]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => ['required']
        ]);
        $shop = Shop::where('user_id', $user->id)->first();
        $saveData = [
            'name' => $data['name'],
            'shop_id'  => $shop->id
        ];
        $category = new Category($saveData);
        $category->save();
        return response()->json([
            'status'  => 'Success',
            'message' => 'Data saved'
        ]);
    }

    public function update(int $id, Request $request)
    {
        Auth::user();
        $data = $request->validate([
            'name' => ['required']
        ]);
        $saveData = [
            'name' => $data['name']
        ];
        $product = new Category($saveData);
        $product->where('id', $id)->update($data);
        return response()->json([
            'status'  => 'Success',
            'message' => 'Data saved'
        ]);
    }

    public function destroy(int $id)
    {
        Auth::user();
        $category = Category::where('id', $id)->first();
        if (!$category) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Data not found'
            ]);
        }

        try {
            $category->delete();
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
