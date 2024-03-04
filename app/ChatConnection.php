<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ChatConnection
 *
 * @property int $id
 * @property array $participants
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ChatConnection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatConnection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatConnection query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatConnection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatConnection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatConnection whereParticipants($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatConnection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChatConnection extends Model
{
    protected $casts = [
        'participants' => 'array'
    ];

    public function isPartOfChat(int $user_id)
    {
        return $this->where('id', $this->getKey())->whereJsonContains('participants', $user_id)->exists();
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }
}
