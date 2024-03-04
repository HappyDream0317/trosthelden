<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingAnswerType.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string|null $description
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerType query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MatchingAnswerType extends Model
{
    const MIXED_COLLAPSE_ID = 12;
    //
}
