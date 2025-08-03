<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ReportSales extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Sales Report';
    }

    public function index()
    {
        $user = Auth::user();
        $shop = Shop::with('branches')->where('user_id', $user->id)->orWhereJsonContains('staff_id', $user->id)->first();
        return view('report.sales.index', [
            'datas' => $shop,
            'title' => $this->title,
        ]);
    }

    public function show(Request $request)
    {
        $branchId = $request->input('branch_id');
        if (! $branchId) {
            return DataTables::of(collect())->make(true);
        }

        if ($request->filled('date_range')) {
            [$start, $end] = explode(' - ', $request->date_range);
            $from = Carbon::parse($start)->startOfDay();
            $to   = Carbon::parse($end)->endOfDay();
        } else {
            $from = Carbon::today()->startOfDay();
            $to   = Carbon::today()->endOfDay();
        }
        $transactions = Transaction::with('branch')
            ->where('branch_id', $branchId)
            ->get();

        $rows = collect();
        foreach ($transactions as $tx) {
            $branch = $tx->branch;
            if (! $branch) continue;

            $decoded = Arr::wrap($tx->transaction);
            foreach ($decoded as $t) {
                $trxDate = isset($t['date']) ? Carbon::parse($t['date']) : null;
                if (! $trxDate || ! $trxDate->between($from, $to)) continue;

                $items = Arr::get($t, 'items', []);
                foreach ($items as $item) {
                    $qty   = Arr::get($item, 'qty', 0);
                    $price = Arr::get($item, 'price', 0);
                    $prodId = Arr::get($item, 'product_id');

                    $rows->push([
                        'branch_id'    => $branch->id,
                        'branch_name'  => $branch->name,
                        'product_id'   => $prodId,
                        'product_name' => Arr::get($item, 'name'),
                        'sold'         => $qty,
                        'total_price'  => $qty * $price,
                    ]);
                }
            }
        }
        $grouped = $rows->groupBy(fn($r) => "{$r['branch_id']}|{$r['product_id']}");
        $productIds = $grouped->keys()->map(fn($key) => explode('|', $key)[1])->unique()->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $report = $grouped->map(function ($group, $key) use ($products, $from, $to) {
            [$branchIdVal, $productId] = explode('|', $key);
            $first = $group->first();
            $date = $from->format('Y-m-d') . '--' . $to->format('Y-m-d');
            return [
                'date'          => $date,
                'branch_id'     => (int) $branchIdVal,
                'branch_name'   => $first['branch_name'],
                'product_id'    => encrypt($productId),
                'product_name'  => $first['product_name'],
                'product_image' => $products[$productId]->image,
                'sold'          => $group->sum('sold'),
                'total_price'   => $group->sum('total_price'),
            ];
        })
            ->sortByDesc('total_price')
            ->values();
        return DataTables::of($report)
            ->addColumn('image', function ($row) {
                $url = route('product.image', $row['product_image']);
                return '<div clas="flex-shrink-0"><img src="' . $url . '" 
                class="rounded img-fluid" 
                    onerror="this.onerror=null;this.src=\'' . asset('assets/images/products/empty-shopping-bag.gif') . '\'" 
                    alt="Product Image" width="70" height="70"></div>';
            })
            ->addColumn('detail', function ($row) {
                return '
                    <a href="' .
                    route('report.sales.detail', [
                        'id'   => $row['product_id'],
                        'date' => $row['date'],
                    ]) . '" class="btn bg-info-subtle text-info"><i class="ti ti-zoom-exclamation fs-4 me-2"></i></a>';
            })
            ->editColumn(
                'total_price',
                fn($row) =>
                'Rp. ' . number_format($row['total_price'], 0, ',', '.')
            )
            ->rawColumns(['detail', 'image'])
            ->make(true);
    }

    public function detail($id, $date)
    {
        $product = Product::with('branch', 'category')
            ->where('id', decrypt($id))
            ->firstOrFail();
        return view('report.sales.detail', [
            'title'       => $this->title,
            'product'     => $product,
            'date'        => $date,
        ]);
    }

    public function chart($id, $date)
    {
        $product = Product::with('branch', 'category')
            // ->where('id', decrypt($id))
            ->where('id', $id)
            ->firstOrFail();
        $transactions = Transaction::query()
            ->where('branch_id', $product->branch_id)
            ->get();
        [$start, $end] = explode('--', $date);
        $report = collect();
        foreach ($transactions as $tx) {
            $decoded = Arr::wrap($tx->transaction);

            foreach ($decoded as $t) {
                $trxDate = isset($t['date'])
                    ? Carbon::parse($t['date'])
                    : null;

                if (! $trxDate || ! $trxDate->betweenIncluded($start, $end)) {
                    continue;
                }

                $items = Arr::get($t, 'items', []);
                foreach ($items as $item) {
                    if (Arr::get($item, 'product_id') != $product->id) {
                        continue;
                    }

                    $qty   = Arr::get($item, 'qty', 0);
                    $price = Arr::get($item, 'price', 0);

                    // hibahkan hanya tanggal saja
                    $report->push([
                        'date'        => $trxDate->toDateString(),  // e.g. "2025-08-02"
                        'date_time'   => $trxDate->format('Y-m-d H:i:s'),
                        'sold'        => $qty,
                        'total_price' => $qty * $price,
                    ]);
                }
            }
        }

        // 3) Group by tanggal dan sum sold & total_price
        $daily = $report
            ->groupBy('date')
            ->map(function ($group) {
                return [
                    // 'date'        => Carbon::parse($date)->format('Y-m-d H:i:s'), // Format tanggal
                    // 'date'        => $group->first()['date_time'],
                    'date'        => Carbon::parse($group->first()['date_time']),
                    'total_sold'  => $group->sum('sold'),
                    'total_sales' => $group->sum('total_price'),
                ];
            })
            ->values();

        // Build series array
        $series = [[
            'name' => 'Total Sales',
            'data' => $daily->map(fn($r) => [
                'x' => $r['date'],
                'y' => $r['total_sold'],
            ])->all(),
        ]];
        return response()->json(compact('series'));
        // return json_encode($series);
    }
}
