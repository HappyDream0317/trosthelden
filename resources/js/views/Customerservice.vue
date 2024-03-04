<template>
    <default-layout>
        <div class="p-3">
            <h1>Kundenservice</h1>
            <br />
            <p>
                Wir sind für dich da. Damit wir dir schnell helfen können, sind
                hier die passenden E-Mail-Adressen, an die du dich wenden
                kannst:
            </p>
            <ul>
                <li>
                    Kündigung deiner Mitgliedschaft: Verwende hierzu bitte den
                    Button "Mitgliedschaft kündigen" in den
                    <router-link :to="{ name: 'settings' }"
                        >Einstellungen</router-link
                    >. Bei Fragen sende uns eine Mail mit deinem Benutzernamen
                    an
                    <a href="mailto:abo@trosthelden.de"
                        >abo(at)trosthelden.de</a
                    >
                </li>
                <li>
                    Melden eines Mitglieds wegen missbräuchlicher Nutzung des
                    TrostHelden-Angebotes ("User melden User"):
                    <a href="mailto:service@trosthelden.de"
                        >service(at)trosthelden.de</a
                    >
                </li>
                <li>
                    Fragen und Anregungen:
                    <a href="mailto:service@trosthelden.de"
                        >service(at)trosthelden.de</a
                    >
                </li>
            </ul>
            <p>
                Schnelle Antworten auf viele Fragen findest du auch
                <a href="/faq">hier</a>.
            </p>
        </div>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
export default {
    name: "Customerservice",
    components: { DefaultLayout },
};
</script>

<style scoped></style>
