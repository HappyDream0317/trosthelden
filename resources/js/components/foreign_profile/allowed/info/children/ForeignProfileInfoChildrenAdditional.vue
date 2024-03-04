<template>
    <div>
        <ForeignProfileInfoChildrenAdditionalHousehold
            v-if="show_children_household"
            :household="children_household"
        />
    </div>
</template>

<script>
import ForeignProfileInfoChildrenAdditionalAffected from "./ForeignProfileInfoChildrenAdditionalAffected";
import ForeignProfileInfoChildrenAdditionalHousehold from "./ForeignProfileInfoChildrenAdditionalHousehold";
export default {
    name: "ForeignProfileInfoChildrenAdditional",
    components: {
        ForeignProfileInfoChildrenAdditionalHousehold,
        ForeignProfileInfoChildrenAdditionalAffected,
    },
    props: ["children_in_household", "children_affected"],
    computed: {
        children_household() {
            if (this.children_in_household === 0) {
                return "keine";
            }

            return this.children_in_household;
        },

        show_children_household() {
            return this.children_in_household !== "";
        },

        show_children_affected() {
            return this.children_affected !== "";
        },
    },
};
</script>

<style scoped></style>
