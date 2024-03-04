<template>
  <default-layout>
    <div class="row">
      <MainContent>
        <template v-if="!currentUser.matching_status">
          <paused-matches-hint/>
        </template>
        <template v-else>
          <survey-completion-hint
              v-if="
                            currentUser.matching_step > -1 && isAllowedTo('view partner')
                        "
          />
          <h2>Deine Trauerfreund-Vorschläge</h2>
          <p v-if="isAllowedTo('view partner')">
            Hier sind deine persönlichen Trauerfreund-Vorschläge. Je
            besser ein Trauerfreund zu dir passt, desto weiter oben
            steht er in der Liste.
            <br/><br/>
            Unser Tipp: Schau dich in Ruhe um und sende mutig eine
            Trauerfreund-Anfrage an andere TrostHelden. In der
            Einzelkommunikation findet ihr heraus, ob ihr zueinander
            passt!
            <br/><br/>
            Kleine Bitte, wenn dich eine Trauerfreundin oder ein Trauerfreund anschreibt, diese Person für dich aber
            nicht passt: Sende ihr eine kurze Absage. Das ist völlig in Ordnung. Und dein Gegenüber hofft nicht
            vergeblich auf Antwort.
            <br/><br/>
            Übrigens: Mit dem Button „Trauerfreund ablehnen“ geht das ganz einfach.
          </p>
          <p v-else>
            Für Trauerhelfer werden aktuell keine Matches berechnet.
          </p>
          <partner-list
              v-if="
                            currentUser.matching_step == -1 &&
                            isAllowedTo('view partner')
                        "
              id="matches_list"
              :key="partnerListKey"
              resource-url="/api/user/matching"
              :empty-message="true"
              class="list-wrapper"
              :perPage="5"
          >
            <template #emptyMessage>
              Keine Matches vorhanden.
            </template>
          </partner-list>
        </template>
      </MainContent>
      <Sidebar class="d-none d-lg-flex">
        <user-info-panel class="mb-2"/>
      </Sidebar>
    </div>
  </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import UserInfoPanel from "../components/UserInfoPanel";
import PartnerList from "../components/partner/PartnerList";
import SurveyCompletionHint from "../components/SurveyCompletionHint";
import PausedMatchesHint from "../components/PausedMatchesHint";
import Sidebar from "../layouts/Sidebar";
import MainContent from "../layouts/MainContent";
import {mapGetters, mapState} from "vuex";

export default {
  name: "Matchings",
  components: {
    MainContent,
    SurveyCompletionHint,
    PartnerList,
    UserInfoPanel,
    DefaultLayout,
    Sidebar,
    PausedMatchesHint,
  },
  data() {
    return {
      partnerListKey: 0,
    };
  },
  computed: {
    ...mapGetters("currentUser", {
      currentUser: "getObject",
      isAllowedTo: "isAllowedTo"
    }),
    ...mapGetters("blockList", {
      users: "blockList",
    }),
  },
  methods: {
    forceRerenderPartnerList() {
      this.partnerListKey += 1;
    },
  },
  watch: {
    users: {
      handler: function (val, oldVal) {
        this.forceRerenderPartnerList();
      },
      deep: true,
    },
  },
};
</script>

<style scoped></style>
