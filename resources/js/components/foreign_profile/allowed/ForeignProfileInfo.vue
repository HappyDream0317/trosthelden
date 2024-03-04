<template>
    <div class="p-4 root">
        <h3 class="d-flex">
            <span class="text-center w-25px flex-shrink-0"
                ><fa-icon icon="user" /></span
            ><span class="ps-3 color--primary font-weight-not-bold h6"
                >Persönliche Angaben</span
            >
        </h3>

        <div class="mb-1 d-flex">
            <div class="text-center w-25px flex-shrink-0">
                <fa-icon icon="home"></fa-icon>
            </div>
            <div class="ps-3">{{ location }}</div>
        </div>

        <div class="mb-1 d-flex">
            <div class="text-center w-25px flex-shrink-0">
                <fa-icon icon="link"></fa-icon>
            </div>
            <div class="ps-3">{{ info.marital_status }}</div>
        </div>

        <ForeignProfileInfoJob v-if="toggleInfoJob" :job="info.job" />

        <ForeignProfileInfoChildren
            v-if="toggleInfoChildren"
            :number_of_children="info.number_of_children"
            :children_in_household="info.children_in_household"
            :children_affected="info.children_affected"
        />
    </div>
</template>

<script>
import ForeignProfileInfoChildren from "./info/ForeignProfileInfoChildren";
import ForeignProfileInfoJob from "./info/ForeignProfileInfoJob";

export default {
    name: "ForeignProfileInfo",
    components: {
        ForeignProfileInfoJob,
        ForeignProfileInfoChildren,
    },
    props: ["info"],
    computed: {
        toggleInfoChildren() {
            return this.info.number_of_children !== "";
        },

        toggleInfoReligion() {
            return this.info["religion"] !== "";
        },
        toggleInfoJob() {
            return this.info["job"] !== "";
        },

        location() {
            let location = this.info.country;
            if (location !== null && location !== "") location += ", ";
            return location + this.info.postal;
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../../../sass/setup/variables";
.root {
    color: #7f7f7f;
}
::v-deep .svg-inline--fa {
    color: $brand-color-primary;
}
</style>
