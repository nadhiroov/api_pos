<?php

namespace App\Http\Controllers\Web;

use App\Models\Shop;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (auth()->user()->hasRole(['owner', 'admin'])) {
            // count all branches
            $countBranch = Branch::query()
                ->whereHas('shop', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->count();
            // count all staff
            $shop = Shop::where('user_id', $user->id)->first();
            $countStaff = count($shop->staff_id ?? []);

            // count all products
            $countProduct = Product::whereHas('branch.shop', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count();

            $today = now()->toDateString(); // '2025-05-18'

            // 1. Ambil semua row transaksi untuk branch + tahun ini
            $allTrx = Transaction::whereHas('branch.shop', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
                ->where('year', now()->year)
                ->get(['transaction']);

            $countTransaction = $allTrx
                ->pluck('transaction')    // kumpulkan array transaksi per row
                ->flatten(1)              // jadi satu array koleksi semua objek transaksi
                ->filter(fn($item) => Str::startsWith($item['date'], $today))
                ->count();

            $countIncome = $allTrx
                ->pluck('transaction')      // kumpulkan array JSON per row
                ->flatten(1)                // jadi satu koleksi panjang
                ->filter(fn($tx) => Str::startsWith($tx['date'], $today))
                ->sum('total');

            $countItems = $allTrx
                ->pluck('transaction')      // array of transaction‐arrays
                ->flatten(1)                // jadi satu koleksi semua {…} transaksi
                ->filter(fn($tx) => Str::startsWith($tx['date'], $today))
                ->pluck('items')            // kini koleksi array-of-arrays items
                ->flatten(1)                // jadi satu list panjang semua item‐objects
                ->sum('qty');
        }else{
            $countBranch = Branch::query()
                ->whereHas('shop', function ($query) use ($user) {
                    $query->whereJsonContains('staff_id', $user->id);
                })
                ->count();

            $shop = Shop::whereJsonContains('staff_id', $user->id)->first();
            $countStaff = count($shop->staff_id ?? []);

            $countProduct = Product::whereHas('branch.shop', function ($q) use ($user) {
                $q->whereJsonContains('staff_id', $user->id);
            })->count();

            $today = now()->toDateString(); // '2025-05-18'

            // 1. Ambil semua row transaksi untuk branch + tahun ini
            $allTrx = Transaction::whereHas('branch.shop', function ($q) use ($user) {
                $q->whereJsonContains('staff_id', $user->id);
            })
                ->where('year', now()->year)
                ->get(['transaction']);

            $countTransaction = $allTrx
                ->pluck('transaction')    // kumpulkan array transaksi per row
                ->flatten(1)              // jadi satu array koleksi semua objek transaksi
                ->filter(fn($item) => Str::startsWith($item['date'], $today))
                ->count();

            $countIncome = $allTrx
                ->pluck('transaction')      // kumpulkan array JSON per row
                ->flatten(1)                // jadi satu koleksi panjang
                ->filter(fn($tx) => Str::startsWith($tx['date'], $today))
                ->sum('total');

            $countItems = $allTrx
                ->pluck('transaction')      // array of transaction‐arrays
                ->flatten(1)                // jadi satu koleksi semua {…} transaksi
                ->filter(fn($tx) => Str::startsWith($tx['date'], $today))
                ->pluck('items')            // kini koleksi array-of-arrays items
                ->flatten(1)                // jadi satu list panjang semua item‐objects
                ->sum('qty');
        }

        return view('dashboard', [
            'title' => 'Dashboard',
            'countBranch' => $countBranch ?? '-',
            'countProduct' => $countProduct,
            'countTransaction' => $countTransaction,
            'countStaff' => $countStaff,
            'countIncome' => $countIncome,
            'countItems' => $countItems,
        ]);
    }
}
