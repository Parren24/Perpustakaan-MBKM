<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    public function index()
    {
        return view('contents.frontend.pages.research.index');
    }
}
