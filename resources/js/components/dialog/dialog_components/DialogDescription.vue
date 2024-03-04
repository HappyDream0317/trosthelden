<template>
    <div style="text-align: center">
        {{ description }}
    </div>
</template>

<script>
export default {
    name: "DialogMessage",
    props: {
        description: String,
    },
};
</script>

<style scoped></style>
