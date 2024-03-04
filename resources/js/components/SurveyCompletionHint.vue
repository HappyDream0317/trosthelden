<template>
    <div class="rounded-box mb-2 p-3">
        <p style="margin-bottom: 0.5rem">
            <strong>Der nächste Schritt</strong>
        </p>

        <youtube-player
            className="rounded-video my-2 frabo-not-complete"
            src="https://www.youtube.com/embed/R08CfkrR1u0"
        ></youtube-player>

        <p>
            Damit wir dir <strong>Trauerfreund-Vorschläge</strong> machen
            können, muss unser Fragebogen komplett ausgefüllt sein.
            <strong>Mach dir das Geschenk und nimm dir kurz Zeit.</strong>
            Du hilfst damit auch deinen potentiellen Trauerfreunden, sich ein
            erstes Bild von dir zu machen.
        </p>
        <router-link
            :to="{ name: 'register' }"
            class="btn-fragebogen btn btn-primary w-100 mt-1 mt-lg-0 mb-1 font-weight-not-bold p-2"
            style="margin-left: auto"
            >Hier geht's zum Fragebogen</router-link
        >
    </div>
</template>

<script>
import YoutubePlayer from "./YoutubePlayer";
export default {
    name: "SurveyCompletionHint",
    components: { YoutubePlayer },
};
</script>

<style scoped>
@media only screen and (min-width: 600px) {
    .btn-fragebogen {
        max-width: 230px;
        margin-left: auto;
    }
}
</style>
