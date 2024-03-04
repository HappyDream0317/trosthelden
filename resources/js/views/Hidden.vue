<template>
    <DefaultLayout>
        <div class="row">
            <MainContent>
                <partner-list
                    id="block_list"
                    :key="partnerListKey"
                    partner-attr-name="user"
                    resource-url="/api/user/blocklist/get"
                    title="Ausgeblendete Mitglieder"
                    intro-message='In dieser Liste siehst du alle von dir ausgeblendeten Mitglieder. Über den Button "Profil einblenden" kannst du Mitglieder wieder einblenden.'
                    :empty-message="true"
                >
                  <template #emptyMessage>
                    Du hast bislang keine Mitglieder ausgeblendet. Das kannst du tun, um die Liste der
                    Trauerfreund-Vorschläge übersichtlich zu halten oder um Mitglieder auszublenden, die du nicht mehr
                    sehen möchtest. Seid ihr bereits Trauerfreunde, musst du eure Trauerfreundschaft aber vorher beenden.
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
    name: "Hidden",
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
