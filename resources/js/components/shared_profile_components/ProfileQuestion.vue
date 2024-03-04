<template>
    <div>
        <label class="h4 font-weight-normal">{{ question }}</label>
        <div class="answer rounded w-100 p-2">
            <textarea disabled>{{
                answer ? answer : "Keine Antwort angegeben"
            }}</textarea>
        </div>
    </div>
</template>

<script>
export default {
    props: ["question", "answer", "editable"],
    name: "ForeignProfileQuestion",
};
</script>

<style scoped lang="scss">
.answer {
    min-height: 110px;
    box-sizing: border-box;
    background: #ededed;
    color: #818181;
}
</style>
