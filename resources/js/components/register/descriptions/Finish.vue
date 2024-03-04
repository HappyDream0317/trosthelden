<template>
    <div class="rounded-box text-center advice">
        <h1>Du hast es geschafft!</h1>
        <p>
            Herzlichen Dank für das Ausfüllen des Fragebogens – und für dein
            Vertrauen. Du bist deinem Trauerfreund einen Riesenschritt
            nähergekommen.
            <br />
            <br />
            <strong>Wie geht’s nun weiter?</strong>
            <br />
            Unser Algorithmus sucht im Hintergrund bereits nach passenden
            Trauerfreunden für dich. Auf deiner persönlichen Startseite werden
            dir in wenigen Sekunden bereits deine ersten Matches angezeigt. All
            deine Matches erreichst du mit einem Klick auf „Alle Matches
            anzeigen“.
            <br />
            <br />
            Es kommen übrigens täglich weitere TrostHelden dazu. Schau daher
            regelmäßig, ob es neue Top-Matches für dich gibt.
            <br />
            <br />
            Noch ein Hinweis: Einige von dir gemachte Angaben übernehmen wir für
            dein persönliches Profil. Die persönlichen Profile helfen, sich ein
            noch genaueres Bild von anderen Mitgliedern zu machen.
            <br />
            <br />
            Das hilft dir insbesondere, einen passenden Trauerfreund zu finden.
        </p>
        <button class="btn btn-primary" @click="$router.push('dashboard')">
            Zur Startseite
        </button>
    </div>
</template>

<script>
export default {
  name: "Finish",
  mounted: function () {
    gtag('event', 'questionnaire_finished')
  }
}
</script>


<style scoped lang="scss" src="../../../../sass/survey_interlude.scss"></style>
