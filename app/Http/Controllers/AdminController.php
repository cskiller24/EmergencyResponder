<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('admin.index');
    }
}
