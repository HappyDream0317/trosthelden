<template>
    <div class="placeholder-bg-layer flex-shrink-0" :class="className" @click="$emit('click')">
        <fa-icon class="icon" icon="user"></fa-icon>
    </div>
</template>

<script>
export default {
    name: "AvatarPlaceholder",
    props: {
        className: String,
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.placeholder-bg-layer {
    background-color: $light-grey-background;
    position: relative;
    border-radius: 50%;

    & > .icon {
        color: $normal-grey-text;
        position: absolute;
        width: 70%;
        height: 70%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: block;
        margin: 0;
    }
}
</style>
