<template>
    <div class="rounded-box text-center advice">
        <h1>Schon ist der erste Teil geschafft!</h1>
        <p>
            Weiter geht es mit ein paar Fragen zu dir. Damit wir die Person für
            dich finden, die perfekt zu dir passt.
        </p>
        <button class="btn btn-primary" @click="$emit('confirmed')">
            Hier geht's weiter
        </button>
    </div>
</template>

<script>
export default {
    name: "AboutYou",
};
</script>

<style scoped lang="scss" src="../../../../sass/survey_interlude.scss"></style>
