<?php

namespace App\Matching;

use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MatchingQueries
{

    /**
     * Fetch all user answers or fetch
     * the answers of a specific user
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */

    public static function fetchUserAnswers($userId = 0)
    {
        $query = DB::table('matching_user_answers')
            ->join('matching_answers', 'matching_user_answers.answer_id', '=', 'matching_answers.id')
            ->select('matching_user_answers.*', 'matching_answers.code', 'matching_answers.answer');

        if ($userId) {
            $query->where('user_id', $userId);
        }
        return $query->get();
    }

    /**
     * Fetch all or specific user
     *
     * @param null $userIdOrNickname
     * @return array
     */

    public static function fetchUsers($userIdOrNickname = null)
    {
        $query = User::select(['id', 'nickname', 'email', 'has_nova_access', 'matching_step']);

        if ($userIdOrNickname && is_numeric($userIdOrNickname)) {
            $query->where('id', $userIdOrNickname);
        }
        if ($userIdOrNickname && is_string($userIdOrNickname)) {
            $query->where('nickname', $userIdOrNickname);
        }
        return $query->get()->all();
    }

    /**
     * Fetch partners for matching process
     *
     * @param $userIdOrNickname
     * @return mixed
     */
    public static function fetchPartners($userIdOrNickname = null)
    {
        $query = User::select(['id', 'nickname', 'email', 'has_nova_access', 'matching_step'])
                ->where('matching_step', '-1')
                ->where('matching_status', true)
                ->whereNotNull('nickname')
                ->whereNotNull('last_seen_at')
                ->where('last_seen_at', '>', Carbon::now()->subDays(config('matching.last-seen')));

        if ($userIdOrNickname && is_numeric($userIdOrNickname)) {
            $query->where('id', $userIdOrNickname);
        }
        if ($userIdOrNickname && is_string($userIdOrNickname)) {
            $query->where('nickname', $userIdOrNickname);
        }
        return $query->get()->all();
    }

    /**
     * Fetch all or specific answer
     *
     * @param int $answerId
     * @return \Illuminate\Support\Collection
     */

    public static function fetchAnswers($answerId = 0)
    {
        $query = DB::table('matching_answers');

        if ($answerId) {
            $query->where('id', $answerId);
        }
        return $query->get();
    }

    /**
     * Fetch the dimensions of the own
     * answers as well as the answers of the partner
     *
     * @return \Illuminate\Support\Collection
     */

    public static function fetchClusterAnswers()
    {
        return DB::table('matching_cluster_dimensions_def')->get();
    }

    /**
     * Fetch the answers of the user
     * and join the cluster_dimension_def table
     * then fetch the parent answer if avaible and
     * add it to the return array
     *
     * @param int $userId
     * @return array
     */

    public static function fetchUserClusterAnswers($userId = 0)
    {
        $answers = DB::table('matching_user_answers')
            ->leftJoin('matching_cluster_dimensions_def', 'matching_user_answers.answer_id', '=', 'matching_cluster_dimensions_def.answer_id_own')
            ->where('matching_user_answers.user_id', $userId)
            ->where('matching_cluster_dimensions_def.id', '>', 0)
            ->get();

        $clusterAnswers = [];
        if ($answers) {
            foreach ($answers as $answer) {
                $clusterAnswers[] = $answer;
                /**
                 * A place for debugging, because this
                 * $ucas[$uca->answer_id_own] = $uca;
                 * was in use, but the key has no functionality - or?,
                 * it was just added and later overwritten in a helper function
                 */
                $parentAnswer = self::fetchParentClusterAnswer($answer->answer_id_own);

                if (count($parentAnswer)) {
                    $clusterAnswers[] = $parentAnswer[0];
                } // its ok, there should be just one answer
            }
        }
        return $clusterAnswers;
    }

    /**
     * Fetch a specific answer and join the related
     * cluster_dimensions_def table
     *
     * @param int $answerId
     * @return \Illuminate\Support\Collection
     */

    public static function fetchParentClusterAnswer($answerId = 0)
    {
        return DB::table('matching_answers')
            ->leftJoin('matching_cluster_dimensions_def', 'matching_answers.depending_on_answer_id', '=', 'matching_cluster_dimensions_def.answer_id_own')
            ->where('matching_answers.id', $answerId)
            ->whereNotNull('matching_cluster_dimensions_def.dimension')
            ->get();
    }


    /**
     * Fetch the rank based on the partern dimensions
     *
     * @param $userPartnerDimensions
     * @return int|mixed
     */

    public static function fetchUserPartnerMatchingRank($userPartnerDimensions)
    {
        $result = DB::table('matching_cluster_ranking_def')
            ->select('rank')
            ->where('dim1', $userPartnerDimensions[1])
            ->where('dim2', $userPartnerDimensions[2])
            ->where('dim3', $userPartnerDimensions[3])
            ->where('dim4', $userPartnerDimensions[4])
            ->where('dim5', $userPartnerDimensions[5])
            ->first();

        return ($result) ? $result->rank : 999;
    }


    /**
     * Fetch the cluster dimensions based on the own
     * and partner answer_id
     *
     * @param int $userAnswerId
     * @param array $parterAnswerId
     * @param bool $debug
     * @return array
     */

    public static function fetchClusterDef($userAnswerId = 0, $parterAnswerId = [], $debug = false)
    {
        if (!$userAnswerId) {
            return [];
        }
        $clusterDefs = DB::table('matching_cluster_dimensions_def')
            ->where('answer_id_own', $userAnswerId)
            ->whereIn('answer_id_partner', $parterAnswerId)
            ->get();
        return ($clusterDefs) ? $clusterDefs->all() : [];
    }

    /**
     * Write the matching to the database. If primary key exists
     * the row will be updated
     *
     * @param $user
     * @param $partner
     * @param string $rank
     * @param string $numeric_weight
     * @return bool
     */

    public static function writeMatching($user, $partner, $rank = "0", $numeric_weight = "0")
    {
        $timestamp = Carbon::now();
        return DB::statement("INSERT INTO matching_user_partner_ranking
            (created_at, updated_at, user_id, partner_id, `rank`, weight) VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE updated_at = ?, `rank` = ?, weight = ?", [
            $timestamp,
            $timestamp,
            $user->id,
            $partner->id,
            $rank,
            $numeric_weight,
            $timestamp,
            $rank,
            $numeric_weight
        ]);
    }
}
