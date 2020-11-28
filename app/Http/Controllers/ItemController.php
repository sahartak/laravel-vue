<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        return View::make('item.items');
    }
}
