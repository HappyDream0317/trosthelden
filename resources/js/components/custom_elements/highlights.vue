<template>
    <div class="highlights">
        <div v-for="highlight in highlights" class="highlights__row">
            <div class="highlights__row__icon">
                <fa-icon class="icon" :icon="highlight.icon" />
            </div>
            <div class="highlights__row__text">
                {{ highlight.text }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["highlights"],
    name: "highlights",
    mounted() {},
};
</script>

<style scoped lang="scss">
@import "../../../sass/setup/variables";
.highlights {
    &__row {
        display: flex;
        margin-bottom: 0.4rem;
        &__icon {
            svg {
                color: $brand-color-primary;
            }
        }
        &__text {
        }
    }
}
</style>
