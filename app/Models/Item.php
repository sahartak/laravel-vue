<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent class Item
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property string $image
 * @property string $active_until
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ItemBid[] $bids
 */
class Item extends Model
{
    protected $guarded = [];
    protected $appends = ['expire_seconds'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bids()
    {
        return $this->hasMany('App\Models\ItemBid');
    }

    public function getExpireSecondsAttribute()
    {
        $now = time();
        $active_until = strtotime($this->active_until);
        $expire_seconds = $active_until > $now ? ($active_until - $now) : 0;
        return $expire_seconds;
    }



}
