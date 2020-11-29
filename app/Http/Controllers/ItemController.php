<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class ItemController extends Controller
{
    public function index()
    {
        return View::make('item.index');
    }

    public function show(int $id)
    {
        return View::make('item.show', compact('id'));
    }
}
