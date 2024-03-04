<template>
    <div class="row mb-2">
        <div class="col-12 rounded px-3 py-2">
            <fa-icon icon="users"></fa-icon>
            <div>
                <p>Deine Trostgruppen</p>
                <br />
                <ProfileGroup
                    v-if="toggleGroups"
                    v-for="group in groups"
                    v-bind:key="group.group_id"
                ></ProfileGroup>
                <div v-else>
                    <router-link :to="{ name: 'groups' }"
                        >Du bist derzeit in keiner Trauergruppe. Hier findest du
                        eine Übersicht.</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ProfileGroup from "./groups/ProfileGroupListingElement";
export default {
    name: "ProfileGroups.vue",
    props: ["groups"],
    components: { ProfileGroup },
    computed: {
        toggleGroups() {
            //console.log("groups");
            //console.log(this.groups);
            return Object.keys(this.groups).length > 0;
        },
    },
};
</script>

<style scoped></style>
