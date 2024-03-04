<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Tooltip.
 *
 * @property int $id
 * @property string $text
 * @property string $component
 * @property string $page
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip wherePage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tooltip whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tooltip extends Model
{
    protected $fillable = [
        'id',
        'section',
        'component',
        'text',
    ];
}
