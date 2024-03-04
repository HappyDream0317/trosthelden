<div style="font-family: courier new, monospace;">
    <?php
    $start = microtime(true);

//    $dbconfig['host'] = 'mariadb';
//    $dbconfig['user'] = 'laravel';
//    $dbconfig['base'] = 'default';
//    $dbconfig['pass'] = 'laravel';

//    $dbconfig['host'] = 'localhost';
//    $dbconfig['user'] = 'mrscnjrufr';
//    $dbconfig['base'] = 'mrscnjrufr';
//    $dbconfig['pass'] = 'bSqD69p95g';
//
//    $dbconfig['char'] = 'utf8';
//
//    $sessionLifetime = 7200;
//
//    try {
//        $pdo = new PDO('mysql:host='.$dbconfig['host'].';dbname='.$dbconfig['base'].';charset='.$dbconfig['char'].';', $dbconfig['user'], $dbconfig['pass']);
//    }
//    catch(PDOException $e) {
//        exit('Unable to connect Database!');
//    }

//    function getUserAnswers($userId = null){
//        global $pdo;
//        if(!empty($userId)) $sql_usr = "AND user_id = $userId";
//
//        $sql = "select a.*, b.code, b.answer
//        from matching_user_answers as a
//            left join matching_answers as b on (b.id = a.answer_id)
//        where  1 ".$sql_usr;
//
//        $sth = $pdo->prepare($sql);
//        $sth->execute();
//
//        while($ua = $sth->fetch(PDO::FETCH_ASSOC)){
//            $userAnswers[] = $ua;
//        }
//
//        return $userAnswers;
//    }
//
//    function getUser($userId = null, $nickname = null){
//        global $pdo;
//        if(!empty($userId))
//            $sql_usr = "AND id = $userId";
//        if(!empty($nickname))
//            $sql_usr = "AND nickname = $nickname";
//        $sql = "select *
//        from users
//        where  1 ".$sql_usr;
//        $sth = $pdo->prepare($sql);
//        $sth->execute();
//        while($u = $sth->fetch(PDO::FETCH_OBJ)){
//            $users[] = $u;
//        }
//        return $users;
//    }
//    function getAnswers($answerId){
//        global $pdo;
//        if(!empty($answerId))
//            $WHERE = "and id = ".$answerId;
//        $sql = "select *
//        from matching_answers
//        where 1 $WHERE";
//
//        $sth = $pdo->prepare($sql);
//        $sth->execute();
//        while($a = $sth->fetch(PDO::FETCH_OBJ)){
//            $as[] = $a;
//        }
//        return $as;
//    }
//    function getClusterAnswers(){
//        global $pdo;
//        $sql = "select *
//        from matching_cluster_dimensions_def
//        where  1 ";
//        $sth = $pdo->prepare($sql);
//        $sth->execute();
//        while($ca = $sth->fetch(PDO::FETCH_OBJ)){
//            $cas[] = $ca;
//        }
//        return $cas;
//    }
//    function getUserClusterAnswers($userId){
//        global $pdo;
//        $sql = "select b.* from matching_user_answers as a
//            left join matching_cluster_dimensions_def as b on (a.answer_id = b.answer_id_own)
//            where a.user_id = ".$userId." and b.id > 0";
//        #echo $sql;
//        $sth = $pdo->prepare($sql);
//        $sth->execute();
//        while($uca = $sth->fetch(PDO::FETCH_OBJ)){
//            $ucas[$uca->answer_id_own] = $uca;
//            // is there a parent user answer id on which this answer depends?
//            // if yes, put into answers array
//            $parentUserClusterAnswer = getParentClusterAnswer($uca->answer_id_own);
//            if(is_object($parentUserClusterAnswer))
//                $ucas[$parentUserClusterAnswer->answer_id_own] = $parentUserClusterAnswer;
//        }
//        return $ucas;
//    }
//    function getParentClusterAnswer($answer_id){
//        global $pdo;
//        $sql = "select b.* from matching_answers as a
//        left join matching_cluster_dimensions_def as b on (a.depending_on_answer_id = b.answer_id_own)
//        where a.id = ".$answer_id."
//            and b.dimension is not null";
//        #echo $sql;
//        $sth = $pdo->prepare($sql);
//        $sth->execute();
//        while($pca = $sth->fetch(PDO::FETCH_OBJ)){
//            $pcas = $pca;
//        }
//        #echo "<pre>"; print_r($pcas); echo "</pre>";
//        if(is_object($pcas))
//            return $pcas;
//        else return false;
//    }
//    function getUserPartnerMatchingRank($userPartnerDimensions){
//        global $pdo;
//        $sql = "select rank from matching_cluster_ranking_def
//        where dim1 = ".$userPartnerDimensions[1]."
//        and dim2 = ".$userPartnerDimensions[2]."
//        and dim3 = ".$userPartnerDimensions[3]."
//        and dim4 = ".$userPartnerDimensions[4]."
//        and dim5 = ".$userPartnerDimensions[5];
//        $sth = $pdo->prepare($sql);
//        $sth->execute();
//        while($rnk = $sth->fetch(PDO::FETCH_ASSOC)){
//            $rank = $rnk['rank'];
//        }
//        if(empty($rank))
//            $rank = 999;
//        return $rank;
//    }
//    function getClusterDef($userAnswerId, $parterAnswerId){
//        if(!is_array($parterAnswerId))
//            return false;
//        global $pdo;
//        $sql = "select * from matching_cluster_dimensions_def
//        where answer_id_own = ".$userAnswerId."
//        and answer_id_partner in (".implode(',', $parterAnswerId).")";
//        echo $sql;
//        $sth = $pdo->prepare($sql);
//        $sth->execute();
//        while($cDef = $sth->fetch(PDO::FETCH_ASSOC)){
//            $clusterDef[] = $cDef;
//        }
//        if(!empty($clusterDef))
//            return $clusterDef;
//        else return false;
//    }
//    function writeMatching($user, $partner, $rank = "0", $numeric_weight = "0"){
//        global $pdo;
//        #created_at 	updated_at 	user_id 	partner_id 	rank 	weight 	first_display 	last_display 	display_number 	first_visit 	last_visit 	visit_number
//        $sql = "insert into matching_user_partner_ranking
//        (created_at, updated_at, user_id, partner_id, rank, weight)
//        values (CURRENT_TIME,CURRENT_TIME, $user->id, $partner->id, $rank, $numeric_weight)
//        on duplicate key update
//            updated_at = CURRENT_TIME, rank = $rank, weight = $numeric_weight";
//
//        #echo $sql;
//        $sth = $pdo->prepare($sql);
//        if($sth->execute())
//            return true;
//    }

    #
    /**
     * get cluster definition (interesting answer ids)
     */
    $clusterAnswers = getClusterAnswers();
    $readyIds = array();
    echo "<b>Alle Cluster-relevanten Antworten:</b>";
    foreach ($clusterAnswers as $cAnswer) {
        if (!in_array($cAnswer->answer_id_own, $readyIds)) {
            $readyIds[] = $cAnswer->answer_id_own;
            echo "<br>".getAnswers($cAnswer->answer_id_own)[0]->answer." (id ".$cAnswer->answer_id_own.", code ".getAnswers($cAnswer->answer_id_own)[0]->code.")";
        }
    }
    echo "<br>--------------------<br>";
    /**
     * get user data
     */
    $uid = $_GET['uid'];
    $nickname = $_GET['nickname'];
    if (empty($uid) && empty($nickname)) {
        $uid = 1;
    }
    if ($_GET['writeAllMatchings'] == 1) {
        unset($uid);
        $displayNone = true;
    } else {
        $displayNone = false;
    }
    #$user = getUser($uid, $nickname)[0];
    $users = getUser($uid, $nickname);

    $i = 0;

    foreach ($users as $user) {
        $userAnswers = getUserAnswers($user->id);

        if (!$displayNone) {
            echo "<b>Userdaten von UserId: ".$user->id." Username: ".$user->nickname."</b><br>";
        }
        #print_r($user);

        $userClusterAnswers = getUserClusterAnswers($user->id);

        if (!$displayNone) {
            echo "<br><u>gegebene relevante Antworten:</u>";
        }
        if (is_object($userClusterAnswers) or is_array($userClusterAnswers)) {
            foreach ($userClusterAnswers as $userClusterAnswer) {
                #print_r($userClusterAnswer);
                if (!$displayNone) {
                    echo "<br>".getAnswers($userClusterAnswer->answer_id_own)[0]->answer." (id ".$userClusterAnswer->answer_id_own.", code ".getAnswers($userClusterAnswer->answer_id_own)[0]->code.")";
                }
            }
        }

        /**
         * get partner data
         */
        $partners = getUser();
        foreach ($partners as $partner) {
            if ($partner->matching_step != -1 || $partner->has_nova_access == 1) {
                continue;
            }
            // cancel, if comparison with same user is in progress
            #if($user->id == $partner->id)
            #    continue;
            if (!$displayNone) {
                echo "<p> >>>>>>>>>>>> </p><b>Nutzervergleich mit user ".$partner->nickname." (id ".$partner->id."): </b>";
            }
            /**
             * get partner cluster answers
             */
            $partnerClusterAnswers = getUserClusterAnswers($partner->id);
            if (!$displayNone) {
                echo "<br><u>vom partner gegebene relevante Antworten:</u>";
            }
            #echo "<pre>"; print_r($partnerClusterAnswers); echo "</pre>";
            if (is_object($partnerClusterAnswers) or is_array($partnerClusterAnswers)) {

                #unset($partnerGivenCAnswers);
                foreach ($partnerClusterAnswers as $pca) {
                    if (!$displayNone) {
                        echo "<br>".getAnswers($pca->answer_id_own)[0]->answer." (id ".$pca->answer_id_own.", code ".getAnswers($pca->answer_id_own)[0]->code.")";
                    }
                    $partnerGivenCAnswers[$pca->answer_id_own] = $pca->answer_id_own;
                }
            }
            /**
             * compare user / partner cluster answers
             * look for every user cluster answer, if a equivalent partner cluster answer is available
             */
            if (is_object($userClusterAnswers) or is_array($userClusterAnswers)) {
                foreach ($userClusterAnswers as $uca) {
                    echo "<br>next answer<br>";
                    print_r($uca);
                    // check for matching partner answers for current user-answer / partner-answers(array) comb
                    $clusterDefs = getClusterDef($uca->answer_id_own, $partnerGivenCAnswers);
                    if (is_array($clusterDefs)) {
                        foreach ($clusterDefs as $clusterDef) {
                            echo "<br>clusterdef-array: <pre>";
                            print_r($clusterDef);
                            echo "</pre>";
                            // get dimension
                            $dim = $clusterDef['dimension'];
                            if (!empty($dim)) {
                                $userPartnerDimension[$partner->id][$dim] = $clusterDef['valuecode'];
                                // exclude?
                                if ($clusterDef['exclusion'] == 1) {
                                    $exclude = true;
                                    echo "<br>AUSSCHLUSSKRITERIUM!";
                                }
                                if (!$displayNone) {
                                    echo "<br><b><em>übereinstimmung gefunden!</em></b>";
                                    echo "<br>dimension/cluster: ".$dim;
                                    echo "<br>userantwort: " .$uca->answer_id_own. " (".getAnswers($uca->answer_id_own)[0]->answer.") ".
                                        "<br>partnerantwort: " .$clusterDef['answer_id_partner']." (".getAnswers($clusterDef['answer_id_partner'])[0]->answer.") ";
                                    echo "<br>Valuecode: ".$clusterDef['valuecode'];
                                }
                            }
                        }
                    }
                    /*
                     if(is_object($partnerClusterAnswers[$uca->answer_id_partner])) {
                         print_r($partnerClusterAnswers[$uca->answer_id_partner]);
                         // get correct cluster/dimension definition
                         #$clusterDef = getClusterDef($uca->answer_id_own, $partnerClusterAnswers[$uca->answer_id_partner]->answer_id_own);
                         #echo "<pre>";print_r($clusterDef);echo "</pre>";
                         // write match into dimensions array
                         // get dimension
                         $dim = $partnerClusterAnswers[$uca->answer_id_own]->dimension;

                         $userPartnerDimension[$partner->id][$dim] = $partnerClusterAnswers[$uca->answer_id_partner]->valuecode;
                         if(!empty($dim)){
                             echo "<br><b><em>übereinstimmung gefunden!</em></b>";
                             echo "<br>dimension/cluster: ".$dim;
                             echo "<br>userantwort: " .$uca->answer_id_own. " (".getAnswers($uca->answer_id_own)[0]->answer.") ".
                                 "<br>partnerantwort: " .$partnerClusterAnswers[$uca->answer_id_partner]->answer_id_own." (".getAnswers($partnerClusterAnswers[$uca->answer_id_partner]->answer_id_own)[0]->answer.") ";
                             echo "<br>Valuecode: ".$userPartnerDimension[$partner->id][$dim];
                         }
                     }*/
                }
            }

            echo"<br>user-partner-dimension:<pre>";
            print_r($userPartnerDimension);
            echo "</pre>";

            // fill empty keys with 0
            for ($i = 1; $i <= 5; $i++) {
                if (!is_array($userPartnerDimension[$partner->id])) {
                    $userPartnerDimension[$partner->id] = array();
                }
                if (!array_key_exists($i, $userPartnerDimension[$partner->id])) {
                    $userPartnerDimension[$partner->id][$i] = 0;
                }
            }
            #if(is_array($userPartnerDimension[$partner->id]))
            ksort($userPartnerDimension[$partner->id]);
            // get rank
            $userPartnerRank = getUserPartnerMatchingRank($userPartnerDimension[$partner->id]);

            if (!$displayNone) {
                echo "<br><u>matching matrix mit user ".$partner->nickname." (id ".$partner->id."): </u>";
                echo "<br>cluster-rang: ".$userPartnerRank." (".implode(' | ', $userPartnerDimension[$partner->id]).")";
            }

            // write into db
            $weight=0;
            if ($exclude == false) {
                if (writeMatching($user, $partner, $userPartnerRank, $weight)) {
                    if (!$displayNone) {
                        echo "<br>Matchings erfolgreich geschrieben.";
                    }
                } else {
                    echo "<br>FEHLER Matchings schreiben!";
                }
            }

            #echo "user cluster answers:<pre>"; print_r($userClusterAnswers); echo "</pre>";
            #echo "partner cluster answers:<pre>"; print_r($partnerClusterAnswers); echo "</pre>";
            /**
             * get remaining answers from partner
             */
            $partnerAnswers = getUserAnswers($partner->id);

            unset($userPartnerDimension, $partnerGivenCAnswers, $exclude);
        }
    }
    #echo "<pre>"; print_r($userAnswers); echo "</pre>";

    $end = microtime(true);
    echo "Dauer: ". number_format($end - $start, 4, ',', '.') ." Sek";
    ?>
</div>
<div style="font-size:9px">
    1.) Datenbank einspielen<br>
    2.)
    Antworten des Users holen
    Andere User holen
    Antworten des Users einzeln mit Antworten der anderen User vergleichen
    -> Achtung: "übergeordnete" Antworten müssen mit verglichen werden > depending_on_answer_id in table answers
    >>> Auswahl "mein leibliches Kind" hat auch "Kind" ausgewählt!
    -> zunächst nur prüfen, ob der User Antworten dimensions_cluster_def (answer_id_own) gewählt hat,
    wenn ja, prüfen, ob Partner diese auch gewählt hat.
    Ähnlichkeiten werden in DB-Verknüpfungen bereits berücksichtigt.
    >>> mit jeder Prüfung muss geschaut werden, ob es einen Ausschluss gibt (exclusion = 1) =>> Vergleich abbrechen, nächster Partner
    5 Matching Dimensionen füllen (array?) $dimarray = array('dim1' => 1, 'dim2' => ...)
    Dimensionen-Array vergleichen mit cluster_ranking_def
    >>> rank heraus holen und usermatch in user_partner_ranking schreiben (gegenseitig)
    Matches hier nacheinander ausgeben, dem rank nach.
</div>
