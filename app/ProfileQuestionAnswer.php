<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProfileQuestionAnswer.
 *
 * @property int $id
 * @property int $profile_question_id
 * @property string $answer_text
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\ProfileQuestion|null $question
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer whereAnswerText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer whereProfileQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfileQuestionAnswer whereUserId($value)
 * @mixin \Eloquent
 */
class ProfileQuestionAnswer extends Model
{
    protected $fillable = [
        'profile_question_id', 'answer_text', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function question()
    {
        return $this->hasOne('App\ProfileQuestion');
    }
}
