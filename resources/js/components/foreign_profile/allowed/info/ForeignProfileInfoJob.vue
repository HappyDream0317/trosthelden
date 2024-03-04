<template>
    <div class="mb-1 d-flex">
        <div class="text-center w-25px flex-shrink-0">
            <fa-icon icon="wrench" />
        </div>
        <div class="ps-3">{{ job }}</div>
    </div>
</template>

<script>
export default {
    name: "ForeignProfileInfoJob",
    props: ["job"],
};
</script>

<style scoped></style>
