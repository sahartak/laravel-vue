<?php
namespace App\Services;


use Illuminate\Support\Facades\Cache;
use App\Models\{Item, ItemBid, User};

class BidService
{
    const BID_INCREMENT = 1;

    public function bid(User $user, int $item_id, ItemService $itemService): ?ItemBid
    {
        $bid = null;
        while ($this->isBidLocked($item_id)) { // waiting until bid will be unlocked
            usleep(50);
        }
        $this->lockBid($item_id);
        $item = $itemService->getById($item_id); // get fresh item from db, because bid could be changed after unlock
        if ($user->hasBalanceForBid($item->price)) {
            $bid = $this->placeBid($user, $item);
        }
        $this->unlockBid($item_id);
        return $bid;
    }

    private function placeBid(User $user, Item $item): ?ItemBid
    {
        $last_bid = $item->price;
        $bid = new ItemBid();
        $bid->item_id = $item->id;
        $bid->amount = $last_bid + static::BID_INCREMENT;
        $bid->user_id = $user->id;
        if ($bid->save()) {
            $item->price = $bid->amount;
            $item->save();
            return $bid;
        }
        return null;
    }

    private function isBidLocked(int $item_id): bool
    {
        $lock_key = $this->getLockKey($item_id);
        return Cache::has($lock_key);
    }

    private function lockBid(int $item_id)
    {
        $lock_key = $this->getLockKey($item_id);
        Cache::put($lock_key, true, 20);
    }

    private function unlockBid(int $item_id)
    {
        $lock_key = $this->getLockKey($item_id);
        Cache::forget($lock_key);
    }

    private function getLockKey(int $item_id): string
    {
        return 'bid_lock_'.$item_id;
    }


    public function getBidsHistory(int $item_id): array
    {
        $bids = ItemBid::query()->where(['item_id' => $item_id])->orderByDesc('id')->get()->toArray();
        return $bids;
    }
}
