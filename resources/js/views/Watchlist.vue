<template>
    <DefaultLayout>
        <div class="row">
            <MainContent>
                <partner-list
                    id="watch_list"
                    :key="partnerListKey"
                    partner-attr-name="user"
                    resource-url="/api/user/watchlist/get"
                    title="Deine Merkliste"
                    :empty-message="true"
                >
                  <template #emptyMessage>
                    Du hast bislang keine Mitglieder zu deiner Merkliste hinzugefügt.
                  </template>
                </partner-list>
            </MainContent>
            <Sidebar class="d-none d-lg-flex">
                <user-info-panel class="mb-2" />
            </Sidebar>
        </div>
    </DefaultLayout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import PartnerList from "../components/partner/PartnerList";
import UserInfoPanel from "../components/UserInfoPanel";
import MainContent from "../layouts/MainContent";
import Sidebar from "../layouts/Sidebar";
import { mapGetters, mapState } from "vuex";

export default {
    name: "Watchlist",
    components: {
        Sidebar,
        MainContent,
        UserInfoPanel,
        PartnerList,
        DefaultLayout,
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

<style scoped></style>
