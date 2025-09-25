<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function informationAndComplaints()
    {
        return view('contents.frontend.pages.service.information-and-complaints');
    }
}
