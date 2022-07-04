<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Position;

class HomeController extends Controller
{
    public function index()
    {


        return view('dashboard');
    }
}
