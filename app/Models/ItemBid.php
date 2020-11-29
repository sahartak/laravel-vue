<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent class ItemBid
 * @property int $id
 * @property int $item_id
 * @property int $user_id
 * @property int $amount
 * @property bool $is_auto
 * @property bool $is_last
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Item $item
 */
class ItemBid extends Model
{
    protected $guarded = [];

    protected $appends = ['user_name'];

    /**
     * Returns Item relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }

    public function getUserNameAttribute(): ?string
    {
        $user = User::getUserById($this->user_id);
        return $user->name ?? null;
    }

    public function getCreatedAtAttribute()
    {
        return $this->updated_at->format('Y-m-d H:i:s');
    }
}
