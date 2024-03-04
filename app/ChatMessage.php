<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ChatMessage.
 *
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string $message
 * @property string $send_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $meta
 * @property string|null $read_at
 * @property-read \App\User $author
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage newQuery()
 * @method static \Illuminate\Database\Query\Builder|ChatMessage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereMeta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereSendAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|ChatMessage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ChatMessage withoutTrashed()
 * @mixin \Eloquent
 */
class ChatMessage extends Model
{
    use SoftDeletes;

    protected $fillable = ['message'];

    public $timestamps = false;

    protected $casts = [
        'send_at' => 'datetime:c'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->withTrashed();
    }
}
