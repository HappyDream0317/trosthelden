<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingQuestionStepContent.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $question_id
 * @property int $step_no
 * @property string|null $content_before
 * @property string|null $content_after
 * @property-read MatchingQuestionStepContent $question
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent whereContentAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent whereContentBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent whereStepNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionStepContent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MatchingQuestionStepContent extends Model
{
    public function question()
    {
        return $this->belongsTo('App\MatchingQuestionStepContent', 'question_id');
    }
}
