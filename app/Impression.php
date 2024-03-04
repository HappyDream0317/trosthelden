<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Impression.
 *
 * @property int $id
 * @property int $user_id
 * @property int $impressionable_id
 * @property string $impressionable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $impressionable
 * @method static \Illuminate\Database\Eloquent\Builder|Impression newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Impression newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Impression query()
 * @method static \Illuminate\Database\Eloquent\Builder|Impression whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Impression whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Impression whereImpressionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Impression whereImpressionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Impression whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Impression whereUserId($value)
 * @mixin \Eloquent
 */
class Impression extends Model
{
    protected $fillable = [
        'user_id',
        'impressionable_id',
        'impressionable_type',
    ];

    public function impressionable()
    {
        return $this->morphTo();
    }
}
