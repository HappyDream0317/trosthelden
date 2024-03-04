<template>
    <div class="actions">
        <div>
            <button @click="$emit('report')">
                <fa-icon class="icon" icon="exclamation-circle"></fa-icon>
                <br />Melden
            </button>
        </div>
        <div>
            <button class="highlight" @click="$emit('quote')">
                <fa-icon class="icon" icon="quote-left"></fa-icon>
                <br />Zitieren
            </button>
            <button class="highlight" @click="$emit('reply')">
                <fa-icon class="icon" icon="comment"></fa-icon>
                <br />Antworten
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "PostActions",
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";
.actions {
    display: flex;

    & > :first-child {
        flex: 1;
    }

    & button {
        border: none;
        background: none;
        line-height: 1.2rem;
        font-weight: 500;
        font-size: 0.75rem;
        color: $normal-grey-text;

        & > .icon {
            font-size: 1.5rem;
            color: $normal-grey-text;
        }
        &.highlight > .icon {
            color: $brand-color-primary;
        }
    }
}
</style>
