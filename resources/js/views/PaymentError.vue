<template>
    <default-layout>
        <div class="row">
            <div class="col-lg-12">
                <h1>Leider ist bei der Bestellung ein Fehler aufgetreten</h1>
                <br />
                <p>
                    Bitte versuche es in einigen Augenblicken erneut oder
                    kontaktiere unsere Support, sollte das Problem weiterhin
                    bestehen.<br />
                    Du erreichst uns per Mail unter service@trosthelden.de
                </p>
                <router-link
                    :to="{ name: 'premium' }"
                    class="btn btn-primary"
                    exact
                    >Erneut versuchen</router-link
                >

                <router-link
                    :to="{ name: 'dashboard' }"
                    class="btn btn-primary"
                    exact
                    >Zur Startseite</router-link
                >
            </div>
        </div>
    </default-layout>
</template>

<script>
export default {
    name: "PaymentError",
    data() {
        return {
            countdown: 5,
        };
    },
    mounted() {
        // const interval = setInterval(async () => {
        //     this.countdown -= 1;
        //     if (this.countdown === 0) {
        //         clearInterval(interval);
        //         this.$index.push({ name: "dashboard" });
        //     }
        // }, 1000);
    },
};
</script>

<style scoped></style>
