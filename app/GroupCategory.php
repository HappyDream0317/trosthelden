<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GroupCategory.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $icon
 * @property int $max_size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Group[] $groups
 * @property-read int|null $groups_count
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory whereMaxSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GroupCategory extends Model
{
    protected $table = 'group_categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'icon', 'max_size',
    ];

    public function groups()
    {
        return $this->hasMany('App\Group', 'category_id');
    }
}
