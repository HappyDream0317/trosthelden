<?php

namespace App\Matching;

use Illuminate\Support\Facades\DB;

class MatchingHelpers
{
    public static function createArrayWithOwnAnswerIdsInKeyAndValue($partnerClusterAnswers, $debug=false)
    {
        $partnerGivenCAnswers = [];
        if ($partnerClusterAnswers) {
            $i = 0;
            foreach ($partnerClusterAnswers as $pca) {
                $i++;
                if ($debug) {
                    MatchingHtml::printPartnerAnswer($pca);
                }
                if ($debug && $i === count($partnerClusterAnswers)) {
                    echo '<br /><br />';
                }
                $partnerGivenCAnswers[$pca->answer_id_own] = $pca->answer_id_own;
            }
        }
        return $partnerGivenCAnswers;
    }

    public static function createEntireDimensionArray($userPartnerDimension, $partner)
    {
        // fills up empty keys with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($userPartnerDimension[$partner->id]) || !is_array($userPartnerDimension[$partner->id])) {
                $userPartnerDimension[$partner->id] = array();
            }
            if (!array_key_exists($i, $userPartnerDimension[$partner->id])) {
                $userPartnerDimension[$partner->id][$i] = 0;
            }
        }
        ksort($userPartnerDimension[$partner->id]);
        return $userPartnerDimension;
    }

    public static function checkIfClusterIsExluded($clusterDef, $exclude)
    {
        if ($clusterDef->exclusion == 1) {
            $exclude = true;
            echo "<br>AUSSCHLUSSKRITERIUM!";
        }
        return $exclude;
    }

    public static function handleDbInsert($exclude, $user, $partner, $userPartnerRank, $debug)
    {
        $weight=0;
        if ($exclude == false) {
            if (MatchingQueries::writeMatching($user, $partner, $userPartnerRank, $weight)) {
                if ($debug) {
                    echo "<br>Matchings erfolgreich geschrieben.";
                }
            } else {
                echo "<br>FEHLER Matchings schreiben!";
            }
        }
    }
}
