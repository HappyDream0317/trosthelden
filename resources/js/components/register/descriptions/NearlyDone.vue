<template>
    <div class="rounded-box text-center advice">
        <h1>Du bist auf der Zielgeraden!</h1>
        <p>Jetzt noch wenige Fragen, dann ist’s vollbracht.</p>
        <button class="btn btn-primary" @click="$emit('confirmed')">
            Weiter geht's
        </button>
    </div>
</template>

<script>
export default {
    name: "NearlyDone",
};
</script>

<style scoped lang="scss" src="../../../../sass/survey_interlude.scss"></style>
