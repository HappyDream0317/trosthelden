<?php

namespace App\Matching;

class MatchingHtml
{

    /**
     * Prints all possible answers and shows possible errors
     */

    public static function printAllAnswers($debug=false)
    {
        $readyIds = [];
        $clusterAnswers = MatchingQueries::fetchClusterAnswers();

        echo "<b>Alle Cluster-relevanten Antworten:</b>";

        foreach ($clusterAnswers as $cAnswer) {
            if (!in_array($cAnswer->answer_id_own, $readyIds)) {
                $readyIds[] = $cAnswer->answer_id_own;
                $answer = MatchingQueries::fetchAnswers($cAnswer->answer_id_own);
                if (!$answer || !isset($answer[0]->answer)) {
                    if ($debug) {
                        self::printError('<br />There is an answer that does not exist or does not has property answer', [$answer, ['answer_id_own' => $cAnswer->answer_id_own]]);
                    }
                } else {
                    echo "<br>".$answer[0]->answer." (id ".$cAnswer->answer_id_own.", code ".$answer[0]->code.")";
                }
            }
        }
        echo "<br>--------------------<br>";
    }

    /**
     * Prints user information when the loop begins
     *
     * @param $user
     */

    public static function printUserLoopHeadline($user)
    {
        echo "<br><b>Userdaten von UserId: ".$user->id." Username: ".$user->nickname."</b><br>";
    }

    /**
     * Prints the user answers
     *
     * @param $userClusterAnswers
     */

    public static function printUserAnswers($userClusterAnswers)
    {
        $unique_ids = [];
        if (is_object($userClusterAnswers) or is_array($userClusterAnswers)) {
            foreach ($userClusterAnswers as $userClusterAnswer) {
                if (isset($userClusterAnswer->answer_id_own) && !in_array($userClusterAnswer->answer_id_own, $unique_ids)) {
                    echo "<br>".MatchingQueries::fetchAnswers($userClusterAnswer->answer_id_own)[0]->answer." (id ".$userClusterAnswer->answer_id_own.", code ".MatchingQueries::fetchAnswers($userClusterAnswer->answer_id_own)[0]->code.")";
                    $unique_ids[] = $userClusterAnswer->answer_id_own;
                }
            }
        }
    }

    /**
     * Prints the Cluster Def array
     *
     * @param $clusterDef
     */

    public static function printClusterArray($clusterDef)
    {
        echo "<br>check clusterdef array: <pre>";
        print_r($clusterDef);
        echo "</pre>";
    }

    /**
     * Indicates the comparison with a specific user
     *
     * @param $partner
     */

    public static function printUserComparisonHeadine($partner)
    {
        echo "<br/><br/><br/><br/><p> >>>>>>>>>>>> </p><b>Nutzervergleich mit user ".$partner->nickname." (id ".$partner->id."): </b>";
        echo "<br><u>vom partner gegebene relevante Antworten:</u>";
    }

    /**
     * Prints the answer of the user
     *
     * @param $pca
     */

    public static function printPartnerAnswer($pca)
    {
        $answer = MatchingQueries::fetchAnswers($pca->answer_id_own);
        echo "<br>".$answer[0]->answer." (id ".$pca->answer_id_own.", code ".$answer[0]->code.")";
    }


    /**
     * Prints an exact match
     *
     * @param $dim
     * @param $uca
     * @param $clusterDef
     */

    public static function printMatchingFound($dim, $uca, $clusterDef)
    {
        echo "<br><b><em>übereinstimmung gefunden!</em></b>";
        echo "<br>cluster db id: ".$clusterDef->id;
        echo "<br>dimension/cluster: ".$dim;
        echo "<br>userantwort: " .$uca->answer_id_own. " (".MatchingQueries::fetchAnswers($uca->answer_id_own)[0]->answer.") ".
            "<br>partnerantwort: " .$clusterDef->answer_id_partner." (".MatchingQueries::fetchAnswers($clusterDef->answer_id_partner)[0]->answer.") ";
        echo "<br>Valuecode: ".$clusterDef->valuecode;
        echo "<br>Weight: ".$clusterDef->weight;
    }

    /**
     * Prints the Cluster MAtching Result
     *
     * @param $userPartnerDimension
     */

    public static function printClusterMatchingResult($userPartnerDimension)
    {
        if ($userPartnerDimension) {
            echo '<br /><br /><b>'.count($userPartnerDimension).' cluster matching result:</b>';
            echo '<pre style="color:#000;">';
            print_r($userPartnerDimension);
            echo '</pre>';
        } else {
            echo '<b>no cluster matching result.</b>';
        }
    }

    /**
     * Prints the Matching Matrix with the partner
     *
     * @param $partner
     * @param $userPartnerRank
     * @param $userPartnerDimension
     */

    public static function printMatchingMatrixWithPartner($partner, $userPartnerRank, $userPartnerDimension, $exclude=false)
    {
        echo "<br><u>matching matrix mit user ".$partner->nickname." (id ".$partner->id."): </u>";
        echo "<br>cluster-rang: ".$userPartnerRank." (".implode(' | ', $userPartnerDimension[$partner->id]).")";
        echo $exclude ? "<br>excluded: match will not be written to database" : "";
    }

    /**
     * Prints an error
     *
     * @param $msg
     * @param $data
     */

    public static function printError($msg, $data)
    {
        echo '<br /><br />Error: <b>'.$msg.'</b>';
        echo '<pre style="color:#000;">';
        print_r($data);
        echo '</pre>';
        echo "Error End<br />";
    }
}
