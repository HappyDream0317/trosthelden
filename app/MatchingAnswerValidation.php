<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingAnswerValidation.
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerValidation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerValidation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerValidation query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerValidation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswerValidation whereName($value)
 * @mixin \Eloquent
 */
class MatchingAnswerValidation extends Model
{
    public $timestamps = false;
}
