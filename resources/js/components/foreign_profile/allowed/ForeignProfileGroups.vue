<template>
    <div class="row mb-2">
        <div class="col-12 rounded px-3 py-2">
            <fa-icon icon="users"></fa-icon>
            <div>
                <p>Trostgruppen von {{ nickname }}</p>
                <br />
                <ForeignProfileGroup
                    v-for="group in groups"
                    v-bind:key="group.group_id"
                ></ForeignProfileGroup>
            </div>
        </div>
    </div>
</template>

<script>
import ForeignProfileGroup from "./groups/ForeignProfileGroupListingElement";
export default {
    name: "ForeignProfileGroups.vue",
    props: ["groups", "nickname"],
    data: () => ({}),
    components: { ForeignProfileGroup },
    mounted: function () {},
};
</script>

<style scoped></style>
