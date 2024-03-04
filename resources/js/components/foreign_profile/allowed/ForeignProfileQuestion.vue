<template>
    <div>
        <h4 class="font-weight-normal">{{ question }}</h4>
        <div class="answer rounded w-100 p-2">
            <p>{{ answer ? answer : "Keine Antwort angegeben" }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ["question", "answer"],
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
