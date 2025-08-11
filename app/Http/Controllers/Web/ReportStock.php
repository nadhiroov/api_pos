<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportStock extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Stock Report';
    }

    public function index()
    {
        $user = Auth::user();
        $shop = Shop::with('branches')->where('user_id', $user->id)->orWhereJsonContains('staff_id', $user->id)->first();
        return view('report.stock.index', [
            'datas' => $shop,
            'title' => $this->title,
        ]);
    }

    public function show(Request $request)
    {
        $userId = Auth::id();
        $branchIds = Branch::query()
            ->whereRelation('shop', 'user_id', $userId)
            ->pluck('id');
        if ($branchIds->isEmpty()) {
            $products = Product::query()->whereRaw('1=0');
        } else {
            $search     = trim((string) $request->input('search[value]', ''));
            $categoryId = $request->input('category');
            $branchId   = $request->input('branch_id');
            [$startDate, $endDate] = (function () use ($request) {
                $dr = trim((string)$request->input('date_range', ''));
                if ($dr && str_contains($dr, '-')) {
                    [$s, $e] = array_map('trim', explode(' - ', $dr, 2));
                    try {
                        return [Carbon::parse($s)->toDateString(), Carbon::parse($e)->toDateString()];
                    } catch (\Throwable $e) {
                    }
                }
                $today = now()->toDateString();
                return [$today, $today];
            })();

            $products = Product::query()
                ->select('id', 'name', 'image', 'stock', 'category_id', 'branch_id', 'price')
                ->with([
                    'history:product_id,in,out',
                    'category:id,name',
                    'branch:id,name',
                ])
                ->whereIn('branch_id', $branchIds)
                ->when($search !== '', function ($q) use ($search) {
                    // escape wildcard agar LIKE akurat
                    $kw = '%' . str_replace(['%', '_'], ['\%', '\_'], $search) . '%';
                    $q->where('name', 'like', $kw);
                })
                // Jika relasi category adalah belongsTo (punya kolom category_id), pakai ini:
                ->when($categoryId, fn($q, $c) => $q->where('category_id', $c))
                ->when($branchId, fn($q, $b) => $q->where('branch_id', $b));
        }
        return DataTables::eloquent($products)
            ->addIndexColumn()
            ->addColumn('sold', function ($product) use ($startDate, $endDate) {
                $total = 0;
                $items = $product->history?->out ?? [];
                foreach ($items as $row) {
                    $d = $row['date'] ?? null;
                    if (!$d) continue;
                    if ($d >= $startDate && $d <= $endDate) {
                        $qty = $row['out'] ?? 0;
                        $total += is_numeric($qty) ? (int)$qty : 0;
                    }
                }
                return $total;
            })
            ->addColumn('restock', function ($product) use ($startDate, $endDate) {
                $total = 0;
                $items = $product->history?->in ?? [];
                foreach ($items as $row) {
                    $d = $row['date'] ?? null;
                    if (!$d) continue;
                    if ($d >= $startDate && $d <= $endDate) {
                        $qty = $row['in'] ?? 0;
                        $total += is_numeric($qty) ? (int)$qty : 0;
                    }
                }
                return $total;
            })
            ->addColumn('image', function ($p) {
                $url = $p->image ? route('product.image', $p->image) : asset('assets/images/products/empty-shopping-bag.gif');
                $fallback = asset('assets/images/products/empty-shopping-bag.gif');
                return '<div class="flex-shrink-0">
                            <img src="' . e($url) . '"
                                class="rounded img-fluid"
                                onerror="this.onerror=null;this.src=\'' . $fallback . '\'"
                                alt="' . e($p->name) . '" width="70" height="70">
                        </div>';
            })
            ->editColumn('stock', function ($p) {
                if (is_null($p->stock)) return '-';
                $cls = $p->stock <= 10 ? 'danger' : ($p->stock <= 20 ? 'warning' : 'success');
                return '<span class="badge text-bg-' . $cls . ' fs-1">' . e($p->stock) . '</span>';
            })
            ->addColumn('price_formatted', function ($product) {
                return 'Rp ' . number_format($product->price, 0, ',', '.');
            })
            ->addColumn('action', function ($p) {
                $detailUrl = route('product.detail', $p->id);

                return '<div class="d-flex align-items-center gap-2">
                            <a href="' . $detailUrl . '" class="btn bg-info-subtle text-info" data-bs-toggle="tooltip" title="Detail">
                                <i class="ti ti-zoom-exclamation fs-4"></i>
                            </a>
                        </div>';
            })
            ->rawColumns(['image', 'stock', 'action'])
            ->toJson();
    }
}
