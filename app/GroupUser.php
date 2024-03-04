<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GroupUser.
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Group $group
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupUser whereUserId($value)
 * @mixin \Eloquent
 */
class GroupUser extends Model
{
    protected $fillable = [
        'group_id', 'user_id',
    ];

    public function group()
    {
        return $this->belongsTo('App\Group');
    }
}
