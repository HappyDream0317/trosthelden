<?php

namespace App;

use App\Models\Utils\CustomizablePageSize;
use Illuminate\Database\Eloquent\Model;

/**
 * App\MatchingUserPartnerRanking.
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property int|null $partner_id
 * @property int|null $rank
 * @property int|null $weight
 * @property string|null $first_display
 * @property string|null $last_display
 * @property int|null $display_number
 * @property string|null $first_visit
 * @property string|null $last_visit
 * @property int|null $visit_number
 * @property-read \App\User|null $partner
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereDisplayNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereFirstDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereFirstVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereLastDisplay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereLastVisit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking wherePartnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereVisitNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchingUserPartnerRanking whereWeight($value)
 * @mixin \Eloquent
 */
class MatchingUserPartnerRanking extends Model
{
    use CustomizablePageSize;

    protected $table = 'matching_user_partner_ranking';
    protected $with = ['partner', 'user'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function partner()
    {
        return $this->belongsTo('App\User', 'partner_id');
    }
}
