<template>
    <div class="d-flex mb-1">
        <div class="text-center w-25px flex-shrink-0">
            <fa-icon icon="school"></fa-icon>
        </div>
        <div class="ps-3">{{ religion }}</div>
    </div>
</template>

<script>
export default {
    name: "ForeignProfileInfoReligion",
    props: ["religion"],
};
</script>

<style scoped></style>
