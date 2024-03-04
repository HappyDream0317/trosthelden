<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileQuestion.
 *
 * @property int $id
 * @property string $text
 * @property int $position
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProfileQuestionAnswer[] $answers
 * @property-read int|null $answers_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestion wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestion whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProfileQuestion extends Model
{
    protected $fillable = [
       'text', 'position',
    ];

    public function answers()
    {
        return $this->hasMany('App\ProfileQuestionAnswer', 'profile_question_id');
    }
}
