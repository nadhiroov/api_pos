<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CustomerController extends Controller
{
    function getStudent()
    {
        $data = Cache::remember('student-data', 600, function () {
            return Http::post(env('API_TSES') . 'getStudents')->json();
        });
        
        return response()->json($data);
    }
}
