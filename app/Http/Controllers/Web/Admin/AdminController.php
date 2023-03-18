<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('admin.index');
    }
}
