<template>
    <div class="root">
        <Accordion :id="id" :label="name">
            <div class="d-flex flex-wrap px-2 px-lg-0">
                <GroupListingElement
                    class="listing-element bg-grey-not-white mb-3"
                    v-for="group in groups"
                    :group_name="group.name"
                    :group_id="group.id"
                    v-bind:key="group.id"
                ></GroupListingElement>
            </div>
        </Accordion>
    </div>
</template>

<script>
import GroupListingElement from "./GroupListingElement";
import Accordion from "../Accordion";
export default {
    // {{name}}<br>
    name: "GroupCategoryListingElement",
    components: { Accordion, GroupListingElement },
    props: ["groups", "name", "id"],
};
</script>

<style lang="scss" scoped>
.root ::v-deep {
    .card {
        margin-bottom: 1rem !important;
    }
    .accordion_row__trigger {
        background-color: #ffffff !important;
    }
}
</style>
