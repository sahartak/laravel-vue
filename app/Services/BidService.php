<?php
namespace App\Services;


use Illuminate\Support\Facades\Cache;
use App\Models\{Item, ItemBid, User};

class BidService
{
    const BID_INCREMENT = 1;

    public function bid(User $user, int $item_id, bool $isAuto, ItemService $itemService): ?ItemBid
    {
        $bid = null;
        while ($this->isBidLocked($item_id)) { // waiting until bid will be unlocked
            usleep(50);
        }
        $this->lockBid($item_id);
        $lastBid = $this->getLastBid($item_id);
        if ($lastBid && $lastBid->user_id == $user->id) { // if last bid made by current user
            $bid = $lastBid;
            if ($bid->is_auto != $isAuto) {
                $bid->is_auto = $isAuto;
                $bid->save();
            }
        } else {
            $item = $itemService->getById($item_id); // get fresh item from db, because bid could be changed after unlock
            if (strtotime($item->active_until) > time()) {
                $bid = $this->placeBid($user, $item, $isAuto);
                if ($bid) {
                    $this->executeAutoBids($bid);
                }
            }
        }
        $this->unlockBid($item_id);
        return $bid;
    }

    /**
     * @param int $item_id
     * @return User[]
     */
    private function getAutoBidUsers(int $item_id): array
    {
        $autoBidUsersIds = ItemBid::query()
            ->where([
                'item_id' => $item_id,
                'is_auto' => 1
            ])
            ->select(['user_id'])
            ->groupBy('user_id')
            ->pluck('user_id')
            ->all();
        $autoBidUsers = [];
        if ($autoBidUsersIds) {
            foreach ($autoBidUsersIds as $autoBidUserId) {
                $autoBidUsers[$autoBidUserId] = User::getUserById($autoBidUserId);
            }
        }
        return $autoBidUsers;
    }

    private function executeAutoBids(ItemBid $lastBid)
    {
        $autoBidUsers = $this->getAutoBidUsers($lastBid->item_id);
        $item = $lastBid->item;
        if ($autoBidUsers) {
            do {
                $isNewBidPlaced = false;
                foreach ($autoBidUsers as $autoBidUser) {
                    if ($autoBidUser->id != $lastBid->user_id) {
                        $autoBidUser->total_bid = $autoBidUser->getTotalBidAmount();
                        $newBid = $this->placeBid($autoBidUser, $item, true);
                        if ($newBid) {
                            $isNewBidPlaced = true;
                            $lastBid = $newBid;
                        }
                    }
                }
            } while($isNewBidPlaced);
        }
    }

    public function getLastBid(int $item_id): ?ItemBid
    {
        $bid = ItemBid::query()->where(['item_id' => $item_id])->orderByDesc('id')->first();
        return $bid;
    }

    private function placeBid(User $user, Item $item, bool $isAuto): ?ItemBid
    {
        if ($user->hasBalanceForBid($item->price + static::BID_INCREMENT)) {
            $last_bid = $item->price;
            $bid = new ItemBid();
            $bid->item_id = $item->id;
            $bid->is_auto = $isAuto;
            $bid->amount = $last_bid + static::BID_INCREMENT;
            $bid->user_id = $user->id;
            if ($bid->save()) {
                $this->resetOldLastBid($bid->item_id, $bid->id);
                $item->price = $bid->amount;
                $item->save();
                return $bid;
            }
        }
        return null;
    }

    private function resetOldLastBid(int $item_id, int $last_id): void
    {
        ItemBid::query()
            ->where([
                'is_last' => 1,
                'item_id' => $item_id
            ])
            ->where('id', '!=', $last_id)
            ->update(['is_last' => 0]);
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


    public function getBidsHistory(int $item_id)
    {
        $bids = ItemBid::query()->where(['item_id' => $item_id])->orderByDesc('id')->get();
        return $bids;
    }

    public function isAutoBidding(User $user, int $item_id): bool
    {
        $userLastBid = ItemBid::query()->where([
            'item_id' => $item_id,
            'user_id' => $user->id
        ])->orderByDesc('id')->first();
        return !empty($userLastBid->is_auto);
    }
}
