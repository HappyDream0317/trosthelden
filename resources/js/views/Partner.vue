<template>
    <default-layout>
        <div class="row">
            <MainContent>
                <partner-list
                    id="invitation_list"
                    :key="`${partnerListKey}_invitation`"
                    title="Deine Trauerfreund-Anfragen"
                    :empty-message="true"
                    resource-url="/api/user/friend/list/invitation"
                    partner-attr-name="user"
                    new-attr-name="first_displayed_at"
                >
                  <template #emptyMessage>
                    Aktuell hast du keine unbeantworteten Trauerfreund-Anfragen.
                  </template>
                </partner-list>
                <br />
                <br />
                <partner-list
                    id="friends_list"
                    :key="`${partnerListKey}_friends`"
                    title="Deine Trauerfreunde"
                    resource-url="/api/user/friend/list"
                    :empty-message="true"
                    partner-attr-name="friend"
                    :customMessage="'Hier werden deine Trauerfreunde angezeigt.'"
                >
                  <template #emptyMessage>
                    Du hast im Moment keine Trauerfreund-Kontakte. Schau doch gleich in deinen Trauerfreund-Vorschlägen
                    nach einem möglichen Trauerfreund.
                    <br>
                    <router-link
                        :to="{ name: 'matchings' }"
                        class="btn btn-primary mt-3"
                    >
                      deinen Trauerfreund-Vorschläge
                    </router-link>
                  </template>
                </partner-list>
            </MainContent>
            <Sidebar class="d-none d-lg-flex">
                <user-info-panel class="mb-2" />
            </Sidebar>
        </div>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import PartnerListItem from "../components/partner/PartnerListItem";
import PartnerList from "../components/partner/PartnerList";
import UserInfoPanel from "../components/UserInfoPanel";
import MainContent from "../layouts/MainContent";
import Sidebar from "../layouts/Sidebar";
import { mapGetters, mapState } from "vuex";

export default {
    name: "Partner",
    components: {
        Sidebar,
        MainContent,
        PartnerList,
        PartnerListItem,
        DefaultLayout,
        UserInfoPanel,
    },
    data() {
        return {
            partnerListKey: 0,
        };
    },
    computed: {
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

<style lang="scss" scoped></style>
