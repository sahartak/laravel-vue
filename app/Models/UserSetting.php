<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent class ItemBid
 * @property int $id
 * @property int $user_id
 * @property int $max_amount
 * @property bool $is_auto
 * @property bool $is_last
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Item $item
 */
class UserSetting extends Model
{


}
