<template>
    <DefaultLayout>
        <div class="groups">
            <fa-icon class="title-icon only--desktop" icon="users"></fa-icon>
            <h1>Trauergruppen</h1>
            <h2 class="font-weight-light">
                Finde die passende Trauergruppe für dich
            </h2>
            <p>
                Du hast die Wahl! Such dir die Trauergruppe aus, die dich am
                meisten anspricht. Du kannst auch in mehreren Trostgruppen dabei
                sein.
            </p>
            <br />

            <div class="rounded-box p-3" style="background-color: #efefef">
                <h3>Trostgruppen-Übersicht</h3>
                <GroupCategoryListingElement
                    v-for="category in categories"
                    v-bind:key="category.id"
                    :id="category.id"
                    :name="category.name"
                    :groups="category.groups"
                ></GroupCategoryListingElement>
            </div>
        </div>
    </DefaultLayout>
</template>

<script>
import GroupCategoryListingElement from "../components/groups/GroupCategoryListingElement";
import DefaultLayout from "../layouts/DefaultLayout";

/**
 * The groups / forum was removed!
 * Maybe it will come back in the future!
 *
 */

export default {
    name: "Groups.vue",
    components: { DefaultLayout, GroupCategoryListingElement },
    data: function () {
        return {
            categories: {},
        };
    },
    mounted: function () {
        axios
            .get("/api/group")
            .then((response) => {
                this.categories = response.data;
            })
            .catch((err) => {
                console.log(err);
            });
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.title-icon {
    color: $brand-color-base;
    font-size: 8rem;
    float: right;
    margin-left: 1rem;
}
</style>
