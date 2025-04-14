<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Transaction::with('branch')->get());
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
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'year' => 'required|integer',
            'transaction' => 'required|array',
        ]);

        $transaction = Transaction::create([
            'branch_id' => $validated['branch_id'],
            'year' => $validated['year'],
            'transaction' => $validated['transaction'],
        ]);

        return response()->json($transaction, 201);
    }

    public function addTransaction(Request $request, $branchId)
    {
        $year = now()->year;

        // Validasi input
        $data = $request->validate([
            'transaction_id' => 'required|string',
            'date' => 'required|date',
            'cashier' => 'required|string',
            'items' => 'required|array',
            'total' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        // Ambil record transaction berdasarkan branch dan tahun
        $trx = \App\Models\Transaction::firstOrCreate(
            ['branch_id' => $branchId, 'year' => $year],
            ['transaction' => []] // default kalau belum ada
        );

        // Ambil transaksi sebelumnya
        $currentTransactions = $trx->transaction ?? [];

        // Tambah transaksi baru ke array
        $currentTransactions[] = $data;

        // Update field transaction
        $trx->update([
            'transaction' => $currentTransactions
        ]);

        return response()->json([
            'message' => 'Transaction added successfully',
            'data' => $data
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::with('branch')->findOrFail($id);
        return response()->json($transaction);
    }

    public function getTransactionsByYearAndBranch(Request $request)
    {
        $request->validate([
            'year' => 'nullable|integer',
            'branch_id' => 'required|exists:branches,id',
            'month' => 'nullable|integer|min:1|max:12',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        $transaction = Transaction::where('branch_id', $request->branch_id)
            ->where('year', $request->year ?? now()->year)
            ->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'No transaction data found for this branch and year.',
            ], 404);
        }

        // Ambil transaksi array
        $transactions = collect($transaction->transaction);

        // Optional: filter berdasarkan bulan
        if ($request->filled('month')) {
            $transactions = $transactions->filter(function ($trx) use ($request) {
                return Carbon::parse($trx['date'])->month === (int) $request->month;
            });
        }

        // Optional: filter berdasarkan rentang tanggal
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $from = Carbon::parse($request->from_date)->startOfDay();
            $to = Carbon::parse($request->to_date)->endOfDay();

            $transactions = $transactions->filter(function ($trx) use ($from, $to) {
                $trxDate = Carbon::parse($trx['date']);
                return $trxDate->between($from, $to);
            });
        }

        // 🔢 Summary
        $totalItems = $transactions->sum(function ($trx) {
            return collect($trx['items'])->sum('qty');
        });

        $totalIncome = $transactions->sum('total');

        return response()->json([
            'branch_id' => $transaction->branch_id,
            'year' => $transaction->year,
            'transaction_count' => $transactions->count(),
            'summary' => [
                'total_items_sold' => $totalItems,
                'total_income' => $totalIncome,
            ],
            'transactions' => $transactions->values(), // reset index
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'branch_id' => 'sometimes|exists:branches,id',
            'year' => 'sometimes|integer',
            'transaction' => 'sometimes|array',
        ]);

        $transaction->update($validated);

        return response()->json($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
