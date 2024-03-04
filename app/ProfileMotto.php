<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileMotto.
 *
 * @property int $id
 * @property string $text
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileMotto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileMotto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileMotto query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileMotto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileMotto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileMotto whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileMotto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileMotto whereUserId($value)
 * @mixin \Eloquent
 */
class ProfileMotto extends Model
{
    protected $fillable = [
        'text',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
