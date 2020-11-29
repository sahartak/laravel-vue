<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\{BidService, ItemService};
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function index(Request $request, ItemService $itemService)
    {
        $items = $itemService->search($request->all());
        return response([
            'data' => $items,
            'user' => $request->user
        ]);

    }

    public function item(Request $request, ItemService $itemService, BidService $bidService, int $id)
    {
        $item = $itemService->getById($id);
        if (!$item) {
            return response(['error' => 'Item not found'], 404);
        }
        return response([
            'data' => [
                'item' => $item,
                'bids_history' => $bidService->getBidsHistory($id)
            ],
            'user' => $request->user
        ]);
    }

    public function bid(Request $request, ItemService $itemService, BidService $bidService, int $id)
    {
        $user = $request->user;
        /* @var \App\Models\User $user */
        $item = $itemService->getById($id);
        if (!$item) {
            return response('Item not found', 404);
        }
        $bid = $bidService->bid($user, $item->id, $itemService);
        if (!$bid) {
            return response(['error' => 'something went wrong!'], 500);
        }
        return response([
            'data' => [
                'bid_amount' => $bid->amount,
                'last_bid' => $bid->item->price,
                'bids_history' => $bidService->getBidsHistory($id)
            ],
            'user' => $request->user
        ]);
    }
}
