<?php

namespace App\Matching;

class Matching
{
    private $userId = 0;
    private $debug;
    private $start;
    private $write;
    private $writeReverse = false;
    private $exclude = false;

    public function __construct($write = false, $debug = false)
    {
        $this->write = $write;
        $this->debug = $debug;
        $this->start = microtime(true);
    }

    /**
     * Each user will be compared with each other user
     */

    public function runForAll()
    {
        $this->init();
    }

    /**
     * Run the Matching for a specific user
     * when a matching is found write them also for the others
     * users to the database.
     *
     * @param int $userId
     * @return bool
     */
    public function runForUser($userId = 0)
    {
        if (!$userId) {
            return false;
        }
        $this->userId = (int) $userId;
        $this->writeReverse = true;
        $this->init();
    }

    /**
     * The matching process
     */

    private function init()
    {
        MatchingHtml::printAllAnswers($this->debug);
        $users = MatchingQueries::fetchUsers($this->userId);
        $partners = MatchingQueries::fetchPartners();
        $environment = config('app.env');

        foreach ($users as $user) {
            if ($user->hasRole('supporter')) {
                continue;
            }

            $userClusterAnswers = MatchingQueries::fetchUserClusterAnswers($user->id);
            MatchingHtml::printUserLoopHeadline($user);
            MatchingHtml::printUserAnswers($userClusterAnswers);

            foreach ($partners as $partner) {
                $found = false;
                $this->exclude = false;

                // Do not consider those users!
                if ($environment === 'production') {
                    if ($partner->matching_step != -1
                        || $partner->has_nova_access == 1
                        || $partner->hasRole('supporter')) {
                        continue;
                    }
                }

                // fetch the answers of the partner
                $partnerClusterAnswers = MatchingQueries::fetchUserClusterAnswers($partner->id);

                // create own answer id array
                $partnerGivenCAnswers = MatchingHelpers::createArrayWithOwnAnswerIdsInKeyAndValue($partnerClusterAnswers, $this->debug);

                MatchingHtml::printUserComparisonHeadine($partner);
                MatchingHtml::printUserAnswers(array_values($partnerClusterAnswers));

                /**
                 * FIRST Step: compare the answers of the user and partner
                 * and create $userPartnerDimension via table matching_cluster_dimensions_def
                 */
                [$userPartnerDimension, $reversePartnerDimension] = $this->createPartnerDimension(
                    $userClusterAnswers,
                    $partnerGivenCAnswers,
                    $partner,
                    $user
                );

                if (!empty($userPartnerDimension)) {
                    $this->finalize($userPartnerDimension, $partner, $user);
                }
                /**
                 * This will be triggered while matching a single user
                 * the $reversePartnerDimension(s) will be saved and inserted as well.
                 * We just have to pass the array and switch $user and $partner parameters.
                 */

                if ($this->writeReverse && !empty($reversePartnerDimension)) {
                    $this->finalize($reversePartnerDimension, $user, $partner, true);
                }
            } // partner loop end
        } // main user loop end

        $end = microtime(true);
        echo "Dauer: ". number_format($end - $this->start, 4, ',', '.') ." Sek";
    }

    /**
     * Creates the partner dimensions based on given answers
     *
     * @param $userClusterAnswers
     * @param $partnerGivenCAnswers
     * @param $partner
     * @param $user
     * @return array[]
     */

    private function createPartnerDimension($userClusterAnswers, $partnerGivenAnswers, $partner, $user)
    {
        $userPartnerDimension = [];
        $reversePartnerDimension = [];

        if ($userClusterAnswers) {
            $cluster_printed = [];

            // loop over each answer of the current user
            foreach ($userClusterAnswers as $uca) {
                /**
                 * now: fetch all partner answer ids from table matching_cluster_dimensions_def
                 * "where the current answer in the loop ($uca->answer_id_own) is IN $partnerGivenCAnswers array"
                 * if not found it's false
                 */

                $clusterDefs = MatchingQueries::fetchClusterDef($uca->answer_id_own, $partnerGivenAnswers, $this->debug);

                if ($clusterDefs) {
                    foreach ($clusterDefs as $clusterDef) {
                        if ($this->debug) {
                            MatchingHtml::printClusterArray($clusterDef);
                        }

                        $dim = $clusterDef->dimension;
                        if (!empty($dim)) {

                            /**
                             * checks the last column in the table matching_cluster_dimension_def.
                             * when it is "1" the answers should not match and the user should not be
                             * proposed to the other. like "plötzlicher kindstot" and "schwangerschaftsabbruch"
                             * the entire cluster will set to zero on all dimensions.
                             * when there is a need to extend the table keep an eye to a fake dimension
                             * and other fake parameters  next to the own and partner_answer_id
                             * if its empty it won't be considered.
                             */
                            if ($clusterDef->exclusion) {
                                $this->exclude = true;
                                /**
                                 * if there is a exclude on any dimension, reset!
                                 * when its empty all the dimensions will be zero with rank 40
                                 * but anyway: it won't be written to the db!
                                 */
                                $userPartnerDimension = [];
                                $reversePartnerDimension = [];
                            }

                            if (!$this->exclude) {
                                $userPartnerDimension[$partner->id][$dim] = $clusterDef->valuecode;
                                if ($this->writeReverse) {
                                    $reversePartnerDimension[$user->id][$dim] = $clusterDef->valuecode;
                                }
                            }

                            if (!in_array($clusterDef->id, $cluster_printed)) {
                                MatchingHtml::printMatchingFound($dim, $uca, $clusterDef, $this->exclude);
                                $cluster_printed[] = $clusterDef->id;
                            }
//                            $found = true;
                        } else {
                            if ($this->debug) {
                                MatchingHtml::printError('Dimension key is empty', $clusterDef);
                            }
                        }
                    }
                }
            }
        }
        return [$userPartnerDimension, $reversePartnerDimension];
    }

    /**
     * Finalizes the result of the matching while creating the dimensions array
     * and generate the cluster with its rank and insert it to the matching_user_partner_ranking table
     *
     * @param $userPartnerDimension
     * @param $partner
     * @param $user
     * @param $exclude
     */

    private function finalize($userPartnerDimension, $partner, $user, $isReverse=false)
    {

        /**
         * SECOND Step: nothing sepcial. it fills up $userPartnerDimension with empty with zero keys
         * and sorts the array keys asc
         */

        $userPartnerDimension = MatchingHelpers::createEntireDimensionArray($userPartnerDimension, $partner);
        if ($this->debug) {
            MatchingHtml::printClusterMatchingResult($userPartnerDimension);
        }

        /**
         * THIRD Step: create clusters and the rank based on
         * $userPartnerDimension array
         */

        $userPartnerRank = MatchingQueries::fetchUserPartnerMatchingRank($userPartnerDimension[$partner->id]);
        if (!$isReverse) {
            MatchingHtml::printMatchingMatrixWithPartner($partner, $userPartnerRank, $userPartnerDimension, $this->exclude);
        }

        if ($this->write) {
            MatchingHelpers::handleDbInsert($this->exclude, $user, $partner, $userPartnerRank, $this->debug);
        }
    }
}
