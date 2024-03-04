<template>
    <ul v-if="show" :id="id" :class="className">
        <li
            v-for="item in items"
            :class="{ hidden: item.destination === 'marketing' }"
        >
            {{ item.text }}
        </li>
    </ul>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "list",
    props: ["blok"],
    data() {
        return {
            id: "",
            items: "",
            className: "",
        };
    },
    mounted() {
        this.id = this.blok.id;
        this.items = this.blok.items;
        this.className = this.blok.className;
    },
    computed: {
        show() {
            return (
                this.blok.destination === this.destination ||
                this.blok.destination === "global"
            );
        },
        ...mapGetters("storyblok", {
            destination: "destination",
        }),
    },
};
</script>

<style scoped lang="scss">
@import "../../sass/setup/variables";
ul {
    list-style: none;
    margin: 1rem auto;
    @media screen and (min-width: 768px) {
        max-width: 630px;
    }
    li {
        font-size: 18px;
        line-height: 170%;
        color: #000000;
        &.hidden {
            display: none;
        }
    }
    li::before {
        content: "\2022";
        color: $brand-color-primary;
        font-weight: bold;
        display: inline-block;
        width: 1em;
        margin-left: -1em;
    }
}
</style>
