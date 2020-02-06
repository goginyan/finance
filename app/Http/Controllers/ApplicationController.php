<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:operator')->only('operator');
    }

    public function __invoke()
    {
        return view('application');
    }

    public function admin()
    {
        return view('admin.app');
    }
}
