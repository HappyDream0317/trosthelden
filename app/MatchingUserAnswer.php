<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingUserAnswer.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id
 * @property int $answer_id
 * @property string|null $answer_text
 * @property-read \App\MatchingAnswer $answer
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer whereAnswerText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserAnswer whereUserId($value)
 * @mixin \Eloquent
 */
class MatchingUserAnswer extends Model
{
    protected $fillable = ['user_id', 'answer_id', 'answer_text'];

    public function answer()
    {
        return $this->belongsTo('App\MatchingAnswer', 'answer_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
