<template>
    <div class="d-flex mb-1">
        <div class="text-center w-25px flex-shrink-0">
            <fa-icon icon="child" />
        </div>

        <div class="ps-3">
            Kinder: {{ children + children_in_house_hold_text }} <br />
            <slot v-if="number_of_children > 0"
                >Von der Trauer betroffene Kinder:
                {{ children_affected_text }}</slot
            >
        </div>
    </div>
</template>

<script>
export default {
    name: "ForeignProfileInfoChildren",
    props: ["number_of_children", "children_in_household", "children_affected"],
    computed: {
        children() {
            if (this.number_of_children === 0) {
                return "keine";
            } else {
                return this.number_of_children;
            }
        },

        children_in_house_hold_text() {
            if (this.number_of_children > 0 && this.children_in_household > 0) {
                return (
                    " (" + this.children_in_household + " im eigenen Haushalt)"
                );
            }

            return "";
        },

        children_affected_text() {
            return this.children_affected ? "ja" : "nein";
        },
    },
};
</script>

<style scoped></style>
