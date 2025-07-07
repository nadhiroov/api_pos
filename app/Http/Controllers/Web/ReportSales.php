<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Models\Branch;
use App\Models\Transaction;
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
        return view('report.sales.index', [
            'title' => $this->title,
        ]);
    }

    public function show(Request $request)
    {
        if ($request->filled('date_range')) {
            [$start, $end] = explode(' - ', $request->date_range);
            $startDate = Carbon::parse($start)->startOfDay();
            $endDate   = Carbon::parse($end)->endOfDay();
        } else {
            $startDate = Carbon::today()->startOfDay();
            $endDate   = Carbon::today()->endOfDay();
        }
        $user = Auth::user();
        $transactions = Transaction::with('branch.shop');
        $transactions->whereHas('branch.shop', function ($query) use ($user) {
            $query->where('user_id', $user->id)->get();
        });
        $rows = collect();
        foreach ($transactions as $tx) {
            $branch = $tx->branch;
            if (! $branch) {
                continue;
            }

            // Decode JSON transaction array
            $list = is_array($tx->transaction)
                ? $tx->transaction
                : json_decode($tx->transaction, true);

            if (! is_array($list)) {
                continue;
            }

            foreach ($list as $item) {
                $trxDate = Carbon::parse($item['date'] ?? null);
                if (! $trxDate->between($startDate, $endDate)) {
                    continue;
                }
                $rows->push([
                    'branch_id'       => $branch->id,
                    'branch_name'     => $branch->name,
                    'date'            => $trxDate->toDateString(),
                    'transaction_id'  => $item['transaction_id'] ?? null,
                    'total_sales'     => $item['total'] ?? 0,
                    'items_sold'      => collect($item['items'] ?? [])->sum('qty'),
                ]);
            }
        }

        // 4. Group by branch and date, aggregate
        $report = $rows
            ->groupBy(function ($r) {
                return $r['branch_id'] . '|' . $r['date'];
            })
            ->map(function ($group, $key) {
                [$branchId, $date] = explode('|', $key);
                return [
                    'branch_id'       => (int)$branchId,
                    'branch_name'     => $group->first()['branch_name'],
                    'date'            => $date,
                    'tx_count'        => $group->count(),
                    'total_sales'     => $group->sum('total_sales'),
                    'total_items_sold' => $group->sum('items_sold'),
                ];
            })
            ->values();

        // 5. Return as DataTable
        return DataTables::of($report)
            ->editColumn('total_sales', function ($row) {
                return 'Rp. ' . number_format($row['total_sales'], 0, ',', '.');
            })
            ->make(true);
    }
}
