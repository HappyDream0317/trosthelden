<template>
    <div class="mb-2 p-3">
        <h2>Trauerfreundvorschläge für dich</h2>
        <p>
            Hier werden dir deine persönlichen Trauerfreund-Vorschläge
            angezeigt, wenn du das Matching aktiviert hast. Aktuell ist dein
            Trauerfreund-Matching allerdings pausiert.
            <br /><br />
            Du möchtest wieder ins Matching, um einen weitere Trauerfreunde zu
            finden und von anderen gefunden zu werden?
        </p>
        <div class="d-flex justify-content-start">
            <button
                @click="onPauseMarching"
                :disabled="loading"
                class="btn btn-primary text-uppercase"
            >
                Matching aktivieren&nbsp;&nbsp;<i
                    class="icon-arrow-next-light"
                ></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "PausedMatchesHint",
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
                .post(`/api/user/matching/status`, { status: true })
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

<style scoped></style>
