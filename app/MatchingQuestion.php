<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingQuestion.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $parent_id
 * @property int|null $depending_on_answer_id
 * @property string|null $code
 * @property string|null $question
 * @property string|null $label
 * @property int $answer_type_id
 * @property int|null $obligatory
 * @property int|null $conditions_relation_id
 * @property int $position
 * @property string|null $tooltip_title
 * @property string|null $tooltip_content
 * @property-read \App\MatchingQuestionStepContent|null $additionalSteps
 * @property-read \App\MatchingAnswerType $answerType
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MatchingAnswer[] $answers
 * @property-read int|null $answers_count
 * @property-read \App\MatchingQuestionAnswerCondition|null $conditionsRelation
 * @property-read \App\MatchingAnswer|null $dependingOnAnswer
 * @property-read MatchingQuestion|null $parent
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereAnswerTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereConditionsRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereDependingOnAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereObligatory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereTooltipContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereTooltipTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MatchingQuestion extends Model
{
    public function parent()
    {
        return $this->belongsTo('App\MatchingQuestion');
    }

    public function dependingOnAnswer()
    {
        return $this->belongsTo('App\MatchingAnswer');
    }

    public function answerType()
    {
        return $this->belongsTo('App\MatchingAnswerType');
    }

    public function conditionsRelation()
    {
        return $this->belongsTo('App\MatchingQuestionAnswerCondition');
    }

    public function answers()
    {
        return $this->hasMany('App\MatchingAnswer', 'question_id')->whereNull('matching_answers.parent_id');
    }

    public function additionalSteps()
    {
        return $this->hasOne('App\MatchingQuestionStepContent', 'question_id');
    }
}
