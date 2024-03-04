<template>
    <div class="overlay">
        <div class="overlay__spinner spinner-border" role="status">
            <span class="visually-hidden-focusable">Loading...</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "LoadingOverlay",
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.overlay {
    background-color: rgba($brand-color-base, 60%);
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    &__spinner {
        width: 6rem;
        height: 6rem;
        color: $brand-color-primary;
    }
}
</style>
