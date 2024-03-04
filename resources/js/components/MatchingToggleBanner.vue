<template>
    <div
        class="rounded-box text-center py-4 px-3 mb-2 bg--blue-250 matching_toggle"
    >
        <p class="matching_toggle__title">
            Dein Trauerfreund-Matching ist aktiviert
        </p>
        <p class="matching_toggle__text">
            Du hast einen oder mehrere Trauerfreunde gefunden und möchtest im
            Moment keine weiteren Anfragen erhalten?
            <br /><br />
            Dann kannst du das Matching für dich pausieren.
        </p>
        <button
            @click="onPauseMarching"
            :disabled="loading"
            class="btn btn-primary text-uppercase"
        >
            Matching pausieren&nbsp;&nbsp;<i class="icon-arrow-next-light"></i>
        </button>
    </div>
</template>

<script>
export default {
    name: "MatchingToggleBanner",
    data() {
        return {
            loading: false,
            error: null,
        };
    },
    methods: {
        onPauseMarching() {
            this.error = null;
            this.loading = true;
            axios
                .post(`/api/user/matching/status`, { status: false })
                .then(async (response) => {
                    if (response.status === 200 && response.data.success) {
                        await this.$store.dispatch("currentUser/fetch");
                    }
                })
                .catch((err) => {
                    let { response, message } = error;
                    this.error = response.data.message ?? message;
                    console.error(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.matching_toggle {
    &__title {
        color: $brand-color-base;
        text-align: center;
        font-weight: $font-weight-extra-bold;
        font-size: 14px;
        margin-bottom: 1rem;
    }
    &__text {
        color: $brand-color-base;
        text-align: center;
        font-weight: $font-weight-light;
        font-size: 14px;
        margin-bottom: 1rem;
    }
}
</style>
