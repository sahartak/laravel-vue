<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::query()->paginate(config('app.itemsPerPage'));
        $user = $request->user;
        return response(['data' => $items, 'user' => $user]);

    }

    public function item($id)
    {
        $item = Item::query()->find($id);

        return response(['data' => $item]);

    }
}
