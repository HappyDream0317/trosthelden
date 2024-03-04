<template>
    <div>
        <p>
            Wir suchen gerade nach passenden Trauerfreunden für dich. Dies dauert
            ein paar Sekunden.
        </p>
        <p>
            Nutze doch die Zeit, um unsere Profilfragen zu beantworten.<br />
            Unsere Erfahrung zeigt, dass Nutzer mit ausgefüllten Profilen
            wesentlich mehr<br />Trauerfreund-Anfragen erhalten.
        </p>
        <a href="/profile" class="btn btn-primary">Zu meinem Profil</a>
    </div>
</template>

<script>
export default {
    name: "PartnerListCountdown",
    props: {
        countdown: {
            type: Number,
            required: true,
        },
    },
};
</script>

<style scoped></style>
