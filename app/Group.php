<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Group.
 *
 * @property int $id
 * @property int $category_id
 * @property string|null $name
 * @property int $open
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 * @property-read \App\GroupCategory $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GroupUser[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereOpen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends Model
{
    protected $fillable = [
        'category_id', 'name', 'open',
    ];

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function category()
    {
        return $this->belongsTo('App\GroupCategory');
    }

    public function user()
    {
        return $this->hasMany('App\GroupUser', 'group_id');
    }
}
