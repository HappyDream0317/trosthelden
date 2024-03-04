<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserFriendRequest.
 *
 * @property int $id
 * @property int $user_id
 * @property int $added_user_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $first_displayed_at
 * @property string|null $last_displayed_at
 * @property-read \App\User $invited
 * @property-read \App\ChatMessage|null $chatMessage
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereAddedUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereFirstDisplayedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereChatMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereLastDisplayedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserFriendRequest whereUserId($value)
 * @mixin \Eloquent
 * @property int|null $chat_message_id
 */
class UserFriendRequest extends Model
{
    protected $fillable = [
        'user_id', 'added_user_id', 'chat_message_id'
    ];

    protected $with = ['chatMessage'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chatMessage()
    {
        return $this->belongsTo('App\ChatMessage', 'chat_message_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invited()
    {
        return $this->belongsTo('App\User', 'added_user_id');
    }
}
