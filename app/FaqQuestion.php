<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FaqQuestion.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $topic
 * @property string $question
 * @property string $answer
 * @property int $public
 * @property int $position
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FaqQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FaqQuestion extends Model
{
    protected $fillable = [
        'position', 'topic', 'question', 'answer', 'public',
    ];
}
