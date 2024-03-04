<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingQuestionAnswerCondition.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $relation_id
 * @property int|null $related_question_id
 * @property int|null $related_answer_id
 * @property int $visible_if_true
 * @property string|null $before
 * @property string|null $operator_several_conditions
 * @property int|null $answer_id
 * @property string|null $operator
 * @property string|null $answer_content
 * @property string|null $after
 * @property int|null $position
 * @property int|null $next_question_id_if_false
 * @property-read \App\MatchingAnswer|null $answer
 * @property-read \App\MatchingQuestion|null $nextQuestionIfFalse
 * @property-read \App\MatchingAnswer|null $relatedAnswer
 * @property-read \App\MatchingQuestion|null $relatedQuestion
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereAnswerContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereNextQuestionIdIfFalse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereOperator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereOperatorSeveralConditions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereRelatedAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereRelatedQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereRelationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingQuestionAnswerCondition whereVisibleIfTrue($value)
 * @mixin \Eloquent
 */
class MatchingQuestionAnswerCondition extends Model
{
    public function relatedQuestion()
    {
        return $this->belongsTo('App\MatchingQuestion');
    }

    public function relatedAnswer()
    {
        return $this->belongsTo('App\MatchingAnswer');
    }

    public function answer()
    {
        return $this->belongsTo('App\MatchingAnswer');
    }

    public function nextQuestionIfFalse()
    {
        return $this->belongsTo('App\MatchingQuestion', 'next_question_id_if_false');
    }
}
