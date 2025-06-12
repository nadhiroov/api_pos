<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Report extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Report';
    }

    public function index()
    {
        return view('report.index', [
            'title' => $this->title,
        ]);
    }
}
