<?php

namespace App;

use App\Models\Utils\CustomizablePageSize;
use Illuminate\Database\Eloquent\Model;

/**
 * App\UserBlockListItem.
 *
 */
class UserBlockListItem extends Model
{
    protected $table = 'user_blocklist';
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'blocked_user_id');
    }
}
