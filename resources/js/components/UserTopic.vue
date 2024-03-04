<template>
    <span v-if="user.death_relation">{{ topic }}</span>
</template>

<script>
export default {
    name: "UserTopic",
    props: {
        user: Object,
    },
    data() {
        let topic = [`Trauer um ${this.user.mourning_text}`];
        if (this.user.death_reason) {
            topic.push(this.user.death_reason);
        }
        return {
            topic: topic.join(" | "),
        };
    },
};
</script>

<style scoped></style>
