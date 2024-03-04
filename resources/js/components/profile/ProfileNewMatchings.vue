<template>
    <router-link
        v-if="isAllowedTo('view mourner_profile')"
        :to="{ name: 'matchings' }"
        class="d-flex align-items-center justify-content-between px-2 h-60"
    >
        <div class="d-flex align-items-center">
            <div class="w-64">
                <fa-icon
                    icon="user-friends"
                    class="i-lg color--primary me-2 font-icon--big"
                ></fa-icon>
            </div>
            <span>Deine Trauerfreund-Vorschläge</span>
        </div>

        <fa-icon
            icon="chevron-right"
            class="i-lg color--primary font-icon--big"
        />
    </router-link>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "ProfileNewMatchings",
    data: function () {
        return {
            new_matchings: 0,
        };
    },
    mounted() {
        this.$eventBus.on("fetch-new-matches-count", () => {
            this.fetchNewMatchesCount();
        });
    },
    methods: {
        fetchNewMatchesCount() {
            axios
                .get("/api/user/matching/new")
                .then((response) => {
                    this.new_matchings = response.data.total;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
    computed: {
        ...mapGetters("currentUser", {
            currentUser: "getObject",
            isAllowedTo : "isAllowedTo"
        }),
    },
    beforeDestroy() {
        this.$eventBus.off("fetch-new-matches-count");
    },
};
</script>

<style scoped></style>
