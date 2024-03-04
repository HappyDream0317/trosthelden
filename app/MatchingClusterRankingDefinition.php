<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingClusterRankingDefinition.
 *
 * @property int $id
 * @property int|null $rank
 * @property string|null $dim1
 * @property string|null $dim2
 * @property string|null $dim3
 * @property string|null $dim4
 * @property string|null $dim5
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition whereDim1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition whereDim2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition whereDim3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition whereDim4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition whereDim5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingClusterRankingDefinition whereRank($value)
 * @mixin \Eloquent
 */
class MatchingClusterRankingDefinition extends Model
{
    protected $table = 'matching_cluster_ranking_def';
    public $timestamps = false;
}
