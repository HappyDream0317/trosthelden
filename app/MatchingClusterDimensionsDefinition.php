<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingClusterDimensionsDefinition.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $dimension
 * @property int|null $answer_id_own
 * @property int|null $answer_id_partner
 * @property string|null $valuecode
 * @property string|null $weight
 * @property int|null $exclusion
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereAnswerIdOwn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereAnswerIdPartner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereDimension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereExclusion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereValuecode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterDimensionsDefinition whereWeight($value)
 * @mixin \Eloquent
 */
class MatchingClusterDimensionsDefinition extends Model
{
    protected $table = 'matching_cluster_dimensions_def';
}
