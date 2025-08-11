<?php

namespace App\Http\Controllers\Web;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use function League\Uri\UriTemplate\first;

class ProductHistoryWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'History Product';
    }

    function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|string|in:add,subtract',
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        if ($product->quantity <= 0 && $request->input('action') === 'subtract') {
            return response()->json(['message' => 'Cannot subtract from an empty product.'], 400);
        }

        return response()->json(['message' => 'Product history updated successfully.'], 200);
    }

    public function checkStock(Request $request)
    {
        $items = collect($request->all());
        $ids   = $items->pluck('product_id')->unique();
        $products = Product::whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        foreach ($items as $item) {
            $id  = $item['product_id'];
            $qty = $item['qty'];

            if (! isset($products[$id])) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "Product ID {$id} not found"
                ], 404);
            }
            $product = $products[$id];
            if ($product->stock < $qty) {
                return response()->json([
                    'status'  => 'error',
                    'message' => "$product->name is out of stock"
                ], 422);
            }
        }

        DB::transaction(function () use ($items, $products) {
            $today = Carbon::now()->format('Y-m-d');
            foreach ($items as $item) {
                $product = $products[$item['product_id']];
                $qty     = $item['qty'];
                $product->decrement('stock', $qty);
                $history  = ProductHistory::firstOrNew([
                    'product_id' => $product->id,
                ]);
                $outData  = $history->out ?? [];
                $foundIdx = null;

                foreach ($outData as $idx => $record) {
                    if ($record['date'] === $today) {
                        $foundIdx = $idx;
                        break;
                    }
                }

                if ($foundIdx !== null) {
                    $outData[$foundIdx]['out'] += $qty;
                } else {
                    $outData[] = [
                        'date' => $today,
                        'out'  => $qty,
                    ];
                }
                $history->out = $outData;
                $history->save();
            }
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Product saved.'
        ]);
    }

    public function addHistory(Request $request)
    {
        $qty = $request->input('quantity', 0);
        $history  = ProductHistory::firstOrNew([
            'product_id' => $request->input('product_id'),
        ]);
        $inData  = $history->in ?? [];
        $foundIdx = null;
        $today = Carbon::parse($request->input('date', Carbon::now()->format('Y-m-d')))->format('Y-m-d');
        foreach ($inData as $idx => $record) {
            if ($record['date'] === $today) {
                $foundIdx = $idx;
                break;
            }
        }
        if ($foundIdx !== null) {
            $inData[$foundIdx]['in'] += intval($qty);
        } else {
            $inData[] = [
                'date' => $today,
                'in'   => intval($qty),
                'exp'  => $request->input('exp', null),
            ];
        }
        $history->in = $inData;
        $history->save();
        return response()->json([
            'status'  => 'success',
            'message' => 'Product saved.'
        ]);
    }
}
