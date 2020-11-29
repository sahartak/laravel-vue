<?php
namespace App\Services;

use App\Models\Item;

class ItemService
{
    public function search(array $params)
    {
        $query = Item::query();
        $filter = $params['filter'] ?? null;
        $sort = $params['sort'] ?? null;
        if ($filter) {
            $query->where(function($query) use($filter) {
                $query->where('name', 'like', "%{$filter}%");
                $query->orWhere('description', 'like', "%{$filter}%");
            });
        }
        $query->orderBy('price', $sort == 1 ? 'asc' : 'desc');
        $items = $query->paginate(config('app.itemsPerPage'));
        return $items;
    }

    public function getById(int $id): ?Item
    {
        $item = Item::query()->find($id);
        /* @var Item $item*/
        return $item;
    }
}
