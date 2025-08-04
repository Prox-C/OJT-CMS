<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HteController extends Controller
{
    public function dashboard()
    {
        return view('hte.dashboard');
    }

}
