<?php

namespace App\Http\Controllers\Web;
use App\Models\Shop;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Shop Management';
    }

    public function index()
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->orWhereJsonContains('staff_id', $user->id)->first();
        return view('shop.index', [
            'data'  => $shop,
            'title' => $this->title,
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'string|max:255',
            'address' => 'string|max:255',
        ]);
        $user = Auth::user();
        $tempFilename = $request->input('imageInput');
        if (Storage::disk('public')->exists("temp/{$tempFilename}")) {
            Storage::disk('public')->move("temp/{$tempFilename}", "shopLogo/{$tempFilename}");
        } else {
            return response()->json([
                'status'  => 'Error',
                'message' => "Temporary file {$tempFilename} not found."
            ], 404);
        }
        $code = strtoupper(Str::random(6));
        $shop = Shop::where('user_id', $user->id)->first();
        $validated['logo'] = $request['image'];
        $validated['user_id'] = $user->id;
        $validated['code'] = $code;
        $shop = new Shop($validated);
        $shop->save();
        return response()->json([
            'status'  => 'Success',
            'message' => "Shop created successfully.",
            'data'    => $shop,
        ], 200);
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

    public function join(Request $request) {
        $shop = Shop::where('code', $request->code)->first();
        if (!$shop) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Shop not found',
            ]);
        }
        $user = Auth::user();
        $staffIds = $shop->staff_id ?? [];
        if (!in_array($user->id, $staffIds)) {
            array_push($staffIds, $user->id);
            Arr::sort($staffIds);
        }
        $shop->update(['staff_id' => $staffIds]);
        return response()->json([
            'status'  => 'Success',
            'message' => 'You have joined the shop successfully'
        ], 200); 
    }

    public function showImage(string $filename)
    {
        $path = "shopLogo/{$filename}";
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Image not found');
        }
        $file    = Storage::disk('public')->get($path);
        return response($file, 200)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
