<template>
    <div>
        <fa-icon :icon="icon" class=""></fa-icon>
        {{ title }}
    </div>
</template>

<script>
export default {
    name: "DialogTitle",
    props: ["title", "icon"],
};
</script>

<style scoped></style>
