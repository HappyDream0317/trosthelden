<template>
    <div class="text-center">
        <div class="rounded-box p-4">
            <h3>Vielen Dank für deine Registrierung.</h3>
            <p>
                Wir haben dir eine E-Mail gesendet. Bitte klicke auf den darin
                enthaltenen Link, um deine E-Mail-Adresse zu bestätigen. Ohne
                diese Bestätigung ist ein Login künftig nicht möglich. Falls du
                keine E-Mail von uns erhalten hast, schaue bitte in deinen
                Spam-Ordner. <br /><br />
                Du kannst jetzt aber schon starten.
            </p>

            <youtube-player
                className="frabo"
                src="https://www.youtube.com/embed/teuR-X6gL-s"
            >
            </youtube-player>
        </div>
        <div class="rounded-box p-4 my-2">
            <h1>Fragebogen</h1>
            <span>Dein erster Schritt zum Trauerfreund!</span>
            <div class="row mt-4">
                <div class="col-1">
                    <fa-icon icon="star" class="fa-sm color--primary"></fa-icon>
                </div>
                <div class="col-10 ps-0 text-left">
                    Nimm dir ca. 10 bis 15 Minuten Zeit und fülle den Fragebogen
                    in aller Ruhe aus
                </div>
            </div>
            <div class="row">
                <div class="col-1">
                    <fa-icon icon="star" class="fa-sm color--primary"></fa-icon>
                </div>
                <div class="col-10 ps-0 text-left">Sei möglichst ungestört</div>
            </div>
            <div class="row">
                <div class="col-1">
                    <fa-icon icon="star" class="fa-sm color--primary"></fa-icon>
                </div>
                <div class="col-10 ps-0 text-left">
                    Antworte ehrlich und aus dem Bauch heraus
                </div>
            </div>
            <div class="row">
                <div class="col-1">
                    <fa-icon icon="star" class="fa-sm color--primary"></fa-icon>
                </div>
                <div class="col-10 ps-0 text-left">
                    Deine Antworten sind wichtig, damit wir einen persönlichen
                    Trauerfreund für dich zu finden können, der genau zu dir
                    passt.
                </div>
            </div>
            <div class="row">
                <div class="col-1">
                    <fa-icon icon="star" class="fa-sm color--primary"></fa-icon>
                </div>
                <div class="col-10 ps-0 text-left">
                    Einige persönliche Angaben kannst du über dein Profil für
                    andere Mitglieder ausblenden.
                </div>
            </div>
            <p class="text-left">
                Im Anschluss schlagen wir dir mit unserem TrostHelden-Matching
                Menschen vor, die in der gleichen oder einer ähnlichen Situation
                sind wie du. Es wartet auf dich ein tröstender, heilsamer
                Austausch!
            </p>
            <button
                class="btn btn-primary"
                @click="$emit('confirmedAsMourner')"
            >
                Jetzt starten
            </button>
        </div>
        <div class="rounded-box p-4">
            <h3>Zugang für Trauerbegleiter</h3>
            <p>
                Du bist Trauerbegleiter und möchtest dir gern den
                Mitgliederbereich von TrostHelden anschauen?<br />
                Sehr gern. Mit deinem Trauerbegleiter-Zugang kannst du dir ein
                umfassendes Bild von unserem vielfältigen Angebot für trauernde
                Menschen machen.
            </p>
            <button
                class="btn btn-primary"
                @click="$emit('confirmedAsSupporter')"
            >
                Als Trauerbegleiter anmelden
            </button>
        </div>
    </div>
</template>

<script>
import YoutubePlayer from "../../YoutubePlayer";
export default {
    name: "Intro",
    components: { YoutubePlayer },
};
</script>

<style scoped lang="scss" src="../../../../sass/survey_interlude.scss"></style>
<style scoped lang="scss">
.container {
    background: none !important;
    padding: 0 !important;

    & > div {
        margin-bottom: 0.625rem;
        padding: 1.5rem 1.25rem 1.625rem 1.25rem;
    }
}
p {
    margin-top: 1rem !important;
}
</style>
