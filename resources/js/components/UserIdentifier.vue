<template>
    <router-link
        v-if="withLink && user.nickname !== null"
        :to="{
            name: 'foreignProfile',
            params: {
                user_id: user.id,
            },
        }"
    >
        {{ identifier }}
        <fa-icon v-if="withIcon" icon="id-card-alt" />
    </router-link>
    <span v-else>
        {{ identifier }}
        <fa-icon v-if="withIcon" icon="id-card-alt" />
    </span>
</template>

<script>
export default {
    name: "UserIdentifier",
    props: {
        user: {
            type: Object,
            required: true,
        },
        withIcon: {
            type: Boolean,
            default: false,
        },
        withLink: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        identifier() {
            if (this.user.nickname === null) {
                return "Ehemaliges Mitglied (" + this.user.id + ")";
            }

            const { user } = this;

            let identifier = user.nickname;

            if (user.sex) {
                identifier += ` (${user.sex}),`;
            }
            if (user.age) {
                identifier += ` ${user.age} Jahre`;
            }
            return identifier;
        },
    },
};
</script>

<style scoped></style>
