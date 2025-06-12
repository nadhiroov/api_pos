<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TransactionWeb extends Controller
{
    protected $title;

    public function __construct()
    {
        $this->title = 'Transaction';
    }

    public function index()
    {
        $user = Auth::user();
        $shop = Shop::with('branches')->where('user_id', $user->id)->orWhereJsonContains('staff_id', $user->id)->first();
        return view('transaction.index', [
            'datas' => $shop,
            'title' => $this->title,
        ]);
    }

    public function show(Request $request)
    {
        $branchId = $request->input('branch_id', null);
        $date_range = $request->input('date_range', null);
        $dt = explode(' - ', $date_range);
        $record = Transaction::where('branch_id', $branchId);
        if ($date_range != null) {
            /* $record = $record->where('year', '>=', Carbon::parse($dt[0])->format('Y'));
            $record = $record->where('year', '<=', Carbon::parse($dt[1])->format('Y')); */
            $record = $record->whereBetween('year', [Carbon::parse($dt[0])->format('Y'), Carbon::parse($dt[1])->format('Y')]);
        } else {
            $record = $record->where('year', now()->year);
        }
        $record = $record->first();

        if (! $record || empty($record->transaction)) {
            return DataTables::of([])->make(true);
        }
        $list = is_array($record->transaction)
            ? $record->transaction
            : json_decode($record->transaction, true);
        if (! is_array($list)) {
            return DataTables::of([])->make(true);
        }
        if ($date_range != null) {
            $start = Carbon::parse($dt[0])->startOfDay();
            $end   = Carbon::parse($dt[1])->endOfDay();
            $list = collect($list)
                ->filter(
                    fn($trx) =>
                    Carbon::parse($trx['date'])->between($start, $end)
                )
                ->values()
                ->all();
        }
        $rows = collect($list)->map(function ($trx) use ($record) {
            return [
                'id'          => $record->id,
                'transaction_id' => $trx['transaction_id'] ?? '',
                'date'           => $trx['date']           ?? '',
                'total_product'  => count($trx['items']    ?? []),
                'total_item'     => collect($trx['items']   ?? [])
                    ->sum('qty'),
                'total_price'    => $trx['total']          ?? 0,
                'raw'            => $trx,
            ];
        });
        $rows = $rows->sortByDesc('date')->values();
        return DataTables::of($rows)
            ->addColumn('action', function ($row) {
                $json = htmlspecialchars(
                    json_encode($row['raw'], JSON_UNESCAPED_UNICODE),
                    ENT_QUOTES,
                    'UTF-8'
                );
                return "<div class='d-flex align-items-center gap-2'>
                <button  data-bs-toggle='modal' data-bs-target='#detail' data-id='$json' class='btn bg-info-subtle text-info'><i class='ti ti-zoom-exclamation fs-4 me-2'></i></button></div>";
                /* return "<button class=\"btn btn-sm btn-primary\" 
                        onclick=\"showDetail($json)\">
                        Detail
                    </button>"; */
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
