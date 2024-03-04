<template>
  <default-layout>
    <div class="row">
      <main-content :hasSidebar="isPartner">
        <dashboard-intro-widget />
        <code-list-open class="mb-5"/>
        <code-list-assigned/>
      </main-content>
      <sidebar v-if="isPartner">
        <div>
          <partner-code/>
        </div>
      </sidebar>
    </div>
  </default-layout>
</template>

<script>

import CodeListOpen from "../../components/b2b/codes/CodeListOpen.vue";
import CodeListAssigned from "../../components/b2b/codes/CodeListAssigned.vue";
import MainContent from "../../layouts/MainContent.vue";
import Sidebar from "../../layouts/Sidebar.vue";
import PartnerCode from  "../../components/b2b/partner/PartnerCode.vue"
import DashboardIntroWidget from "../../components/b2b/dashboard/IntroWidget.vue";
import {mapGetters} from "vuex";

export default {
  name: "B2BDashboard",
  components: {
    MainContent,
    Sidebar,
    CodeListOpen,
    CodeListAssigned,
    PartnerCode,
    DashboardIntroWidget
  },
  computed: {
    ...mapGetters("currentUser", {
      currentUserId: "getId"
    }),
    ...mapGetters("b2bUser", {
      isPartner: "getIsPartner"
    }),
  },
}
</script>

<style scoped>

</style>