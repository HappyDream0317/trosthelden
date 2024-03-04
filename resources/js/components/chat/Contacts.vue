<template>
    <ul
        v-if="contacts && contacts.length > 0"
        class="contact-list"
        role="tablist"
    >
        <li
            v-for="contact in contacts"
            :class="{ active: selected === contact.id }"
            role="tab"
        >
            <a
                @click="
                    () => {
                        selectContact(contact);
                    }
                "
            >
                <img
                    v-if="contact.avatar !== null"
                    class="avatar"
                    :src="getContactAvatar(contact)"
                    alt="Benutzer Profilbild"
                />
                <avatar-placeholder v-else class="avatar"></avatar-placeholder>
                <div>
                    <user-identifier
                        :user="contact"
                        class="user-identifier"
                    ></user-identifier>
                    <br />
                    <user-location :user="contact"></user-location>
                </div>
                <br />
                <span v-if="unreadCountPerChat[contact.id]" class="msgCount">{{
                    unreadCountPerChat[contact.id]
                }}</span>
            </a>
        </li>
    </ul>
    <div
        v-else
        class="d-flex justify-content-center align-items-center text-center"
        style="padding-top: 1rem"
    >
        <div v-if="loading" class="my-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden-focusable">Loading...</span>
            </div>
        </div>
        <p v-else>Hier werden in Zukunft deine Trauerfreunde angezeigt.</p>
    </div>
</template>

<script>
import UserIdentifier from "../UserIdentifier";
import AvatarPlaceholder from "../AvatarPlaceholder";
import UserLocation from "../UserLocation";
import { mapGetters, mapState } from "vuex";

export default {
    name: "Contacts",
    components: { UserLocation, AvatarPlaceholder, UserIdentifier },
    data() {
        return {
            selected: null,
            loading: true,
        };
    },
    mounted() {
        if (this.contacts !== undefined) this.loading = false;
    },
    watch: {
        contacts: function (value) {
            if (this.contacts !== undefined) this.loading = false;
        },
    },
    computed: {
        ...mapState("chat", {
            unreadCountPerChat: "unreadCountPerChat",
        }),
        ...mapState("partner", {
            contacts: "allPartners",
        }),
    },
    methods: {
        unreadHasContactId(contactId) {
            return Object.keys(this.unreadHasUserId).includes(contactId);
        },
        selectContact(contact) {
            if (
                !this.selected ||
                (contact && this.selected && contact.id !== this.selected.id)
            ) {
                this.selected = { ...contact };
                this.$emit("selected-contact", contact);
                this.$eventBus.emit("selected-contact", contact);
            }
        },
        getContactAvatar(contact) {
            return `${window.location.origin}/${contact.avatar}`;
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";
@import "../../../sass/scrollbar";
@import "../../../sass/micro-clearfix";

ul.contact-list {
    list-style: none;
    padding: 0;
    margin: 0;
    background-color: $brand-color-base;
    height: 100%;
    width: 100%;
    overflow-x: hidden;
    overflow-y: scroll;
    font-size: 0.75rem;
    line-height: 1rem;

    @media only screen and (max-width: 1024px) {
        @include scroll-bar($light-grey-background, $brand-color-primary);
    }

    & > li {
        bsorder-bottom: solid 1px $light-grey-background;

        a {
            color: $normal-grey-text;
            display: flex;
            cursor: pointer;
            padding: 0.5rem 1.25rem 0.625rem 0.75rem;
            width: 100%;
            position: relative;
            align-items: center;
        }

        &.active a {
            background-color: $light-grey-background;
        }

        .avatar {
            width: 3.5rem;
            height: 3.5rem;
            margin-right: 1rem;
            float: left;
            border-radius: 50%;
        }

        @include micro-clear;
    }
}

.user-identifier {
    color: $brand-color-primary;
    font-weight: 500;
}

.topic {
    padding-top: 0.2rem;
    display: block;
    text-align: right;
    bottom: 0;
    right: 0;
    color: $light-grey-text;
    font-size: 0.625rem;
}

.msgCount {
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 1rem;
    font-weight: 600;
    border: 3px solid $brand-color-base;
    background: $brand-color-primary;
    color: $brand-color-base;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: auto;
}
</style>
