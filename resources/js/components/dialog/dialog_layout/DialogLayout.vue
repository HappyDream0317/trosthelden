<template>
    <div class="placeholder_modal_design_layout">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "DialogLayout",
};
</script>

<style scoped>
.placeholder_modal_design_layout {
    min-height: 100px;
    background-color: white;
}
</style>
