<?php

namespace App;

use App\Models\Utils\CustomizablePageSize;
use Illuminate\Database\Eloquent\Model;

/**
 * App\UserWatchListItem.
 *
 * @property int $id
 * @property int $user_id
 * @property int $watched_user_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserWatchListItem whereWatchedUserId($value)
 * @mixin \Eloquent
 */
class UserWatchListItem extends Model
{
    use CustomizablePageSize;

    protected $table = 'user_watchlist';
    protected $with = ['user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'watched_user_id');
    }
}
