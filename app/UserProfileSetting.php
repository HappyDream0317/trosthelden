<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserProfileSetting.
 *
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfileSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfileSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfileSetting query()
 * @mixin \Eloquent
 */
class UserProfileSetting extends Model
{
    protected $fillable = [
        'visibility_info_job', 'visibility_info_religion', 'visibility_info_children',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
