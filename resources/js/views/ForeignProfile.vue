<template>
    <DefaultLayout>
        <template v-if="hasLoaded">
            <ForeignProfilePage
                v-if="displayProfile"
                :user_id="user_id"
            ></ForeignProfilePage>
            <ForeignProfileBlockedPage v-else></ForeignProfileBlockedPage>
        </template>
    </DefaultLayout>
</template>
<script>
import DefaultLayout from "../layouts/DefaultLayout";
import ForeignProfilePage from "../components/foreign_profile/allowed/ForeignProfilePage";
import ForeignProfileBlockedPage from "../components/foreign_profile/blocked/ForeignProfileBlockedPage";
export default {
    components: {
        DefaultLayout,
        ForeignProfilePage,
        ForeignProfileBlockedPage,
    },
    props: ["user_id"],
    data: function () {
        return {
            hasLoaded: false,
            blocked: true,
            response_data: {},
        };
    },
    mounted: function () {
        this.getBlockStatus();
    },
    methods: {
        getBlockStatus() {
            axios
                .get("/api/user/blocklist/" + this.user_id)
                .then((response) => {
                    this.blocked = response.data.status;
                    this.hasLoaded = true;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
    computed: {
        displayProfile() {
            return this.hasLoaded && !this.blocked;
        },
    },
};
</script>

<style lang="scss" scoped></style>
