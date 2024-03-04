<template>
    <div class="wrapper rounded">
        <h2>Entschuldigung! Ein Fehler ist aufgetreten.</h2>
        <p>
            Bitte beantworte die folgende(n) Frage(n), damit unser
            Matching-Algorithmus einen passenden Trauerfreund für dich finden
            kann:
        </p>
        <ul>
            <li v-for="question in missingQuestions" :key="question.id">
                {{ question.question }}{{ getReason(question.id) }}
            </li>
        </ul>
    </div>
</template>

<script>
const errorMsg = {
    1: "Du musst mindestens 16 Jahre alt sein, um unser Angebot zu nutzen.",
    2: "Deine Eingabe ist ungültig",
    3: "Bitte gib eine zusätzliche Beschreibung ein.",
    4: "Dein Text ist zu lang. Bitte kürze ihn etwas.",
    5: "Bitte gib eine gültige Zahl ein.",
    6: "Deine Postleitzahl ist ungültig. Bitte verwende eine gültige 5-stellige Postleitzahl. Wir benötigen diese, damit wir Dir Trauerfreunde in Deiner Nähe zeigen können.",
    7: "Deine Postleitzahl ist ungültig. Bitte verwende eine gültige 4-stellige Postleitzahl. Wir benötigen diese, damit wir Dir Trauerfreunde in Deiner Nähe zeigen können.",
};

export default {
    name: "ErrorModal",
    props: {
        missingQuestions: Array,
        validationErrors: [Object, Array],
    },
    methods: {
        getReason(questionId) {
            if (
                typeof this.validationErrors !== "object" ||
                !this.validationErrors.hasOwnProperty(questionId)
            ) {
                return "";
            }
            return " - " + errorMsg[this.validationErrors[questionId][0].code];
        },
    },
};
</script>

<style scoped>
.wrapper {
    padding: 0.625rem;
}
</style>
