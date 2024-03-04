<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingAnswer.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $question_id
 * @property int|null $parent_id
 * @property int|null $depending_on_answer_id
 * @property string|null $code
 * @property string|null $answer
 * @property string|null $label
 * @property int|null $additional_text
 * @property string|null $description
 * @property int|null $different_answer_type_id
 * @property int|null $obligatory
 * @property int|null $numeric_min
 * @property int|null $numeric_max
 * @property int|null $termination_condition
 * @property int|null $next_question_id
 * @property int|null $conditions_relation_id
 * @property int $position
 * @property int|null $matching_answer_validation_id
 * @property string|null $tooltip_title
 * @property string|null $tooltip_content
 * @property int|null $weight
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\MatchingQuestionAnswerCondition[] $conditionsRelation
 * @property-read int|null $conditions_relation_count
 * @property-read \Illuminate\Database\Eloquent\Collection|MatchingAnswer[] $dependingAnswers
 * @property-read int|null $depending_answers_count
 * @property-read MatchingAnswer|null $dependingOnAnswer
 * @property-read \App\MatchingAnswerType|null $differentAnswerType
 * @property-read \App\MatchingQuestion|null $nextQuestion
 * @property-read \Illuminate\Database\Eloquent\Collection|MatchingAnswer[] $parent
 * @property-read int|null $parent_count
 * @property-read \App\MatchingQuestion|null $question
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer dependentAnswers($answer_id)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereAdditionalText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereConditionsRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereDependingOnAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereDifferentAnswerTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereMatchingAnswerValidationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereNextQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereNumericMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereNumericMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereObligatory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereTerminationCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereTooltipContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereTooltipTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingAnswer whereWeight($value)
 * @mixin \Eloquent
 */
class MatchingAnswer extends Model
{
    public function scopeDependentAnswers($query, $answer_id)
    {
        //TODO: Completely trash
        return $query->where('depending_on_answer_id', $answer_id)->orWhereNull('depending_on_answer_id');
    }

    public function parent()
    {
        return $this->hasMany('App\MatchingAnswer', 'parent_id');
    }

    public function question()
    {
        return $this->belongsTo('App\MatchingQuestion');
    }

    public function dependingAnswers()
    {
        return $this->hasMany('App\MatchingAnswer', 'depending_on_answer_id');
    }

    public function dependingOnAnswer()
    {
        return $this->belongsTo('App\MatchingAnswer');
    }

    public function differentAnswerType()
    {
        return $this->belongsTo('App\MatchingAnswerType');
    }

    public function nextQuestion()
    {
        return $this->belongsTo('App\MatchingQuestion', 'next_question_id');
    }

    public function conditionsRelation()
    {
        return $this->hasMany('App\MatchingQuestionAnswerCondition', 'related_answer_id');
    }
}
