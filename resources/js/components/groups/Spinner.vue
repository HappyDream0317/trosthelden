<template>
    <div
        class="spinner-wrapper d-flex flex-column justify-content-center align-items-center"
    >
        <span class="mb-2" :class="color" v-if="message">{{ message }}</span>
        <div class="spinner-grow text-primary duration-1500" role="status">
            <span class="visually-hidden-focusable">Loading...</span>
        </div>
    </div>
</template>

<script>
export default {
    name: "Spinner",
    props: {
        color: String,
        message: {
            type: String,
            default: null,
        },
    },
    data() {
        return {};
    },
};
</script>

<style lang="scss" scoped>
.spinner-wrapper {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    span {
        line-height: 1.5;
        @media only screen and (max-width: 1023px) {
            text-align: center;
        }
    }
}
</style>
