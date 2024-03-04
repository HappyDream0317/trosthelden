<?php

namespace App;

use App\Models\Utils\CustomizablePageSize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * App\UserFriend.
 *
 * @property int $id
 * @property int $user_id
 * @property int $foreign_user_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $friend
 * @property-read \App\User $initiatingFriend
 * @property-read \App\User $requestedFriend
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend whereForeignUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriend whereUserId($value)
 * @mixin \Eloquent
 */
class UserFriend extends Model
{
    use CustomizablePageSize;
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'foreign_user_id',
    ];
    protected $with = ['requestedFriend', 'initiatingFriend'];
    protected $appends = ['friend'];

    public function initiatingFriend()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requestedFriend()
    {
        return $this->belongsTo(User::class, 'foreign_user_id');
    }

    public function getFriendAttribute()
    {
        $friend = $this->requestedFriend()->first();
        return (isset($friend->id) && Auth::id() === $friend->id) ? $this->initiatingFriend()->first() : $this->requestedFriend()->first();
    }
}
