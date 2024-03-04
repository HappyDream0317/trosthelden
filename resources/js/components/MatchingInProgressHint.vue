<template>
    <div class="rounded-box">
        <h4>Liebe TrostHeldin, lieber TrostHeld,</h4>
        <p>
            da wir gerade erst online gegangen sind, gehörst du zu den
            allerersten TrostHelden! Schön, dass du dabei bist!
        </p>
        <div class="text-center">
            <img src="/img/loading-colored.gif" class="loading-img" />
        </div>
        <p>
            Um in unserer speziellen Trost-Datenbank genau den Menschen zu
            finden, der exakt zu dir passt, müssen sich zunächst einige
            Trostsuchende auf unserer Plattform TrostHelden anmelden. Sobald wir
            Trauerfreund-Vorschläge für dich gefunden haben, informieren wir
            dich umgehend.
        </p>
        <p>
            Herzliche Grüße<br />
            Dein TrostHelden-Team
        </p>
    </div>
</template>

<script>
export default {
    name: "MatchingInProgressHint",
};
</script>

<style scoped>
.loading-img {
    width: 16rem;
    height: auto;
}
</style>
