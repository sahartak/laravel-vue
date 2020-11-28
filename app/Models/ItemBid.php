<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent class ItemBid
 * @property int $id
 * @property int $item_id
 * @property int $user_id
 * @property float $bid_amount
 * @property string $bid_on
 *
 * @property Item $item
 */
class ItemBid extends Model
{
    protected $guarded = [];

    public $timestamps = false;


    /**
     * Returns Item relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }
}
