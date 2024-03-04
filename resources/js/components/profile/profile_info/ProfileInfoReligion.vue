<template>
    <InfoVisibilityToggle icon="school" v-model="visible">
        {{ religion }}
    </InfoVisibilityToggle>
</template>

<script>
import InfoVisibilityToggle from "./InfoVisibilityToggle";
export default {
    name: "ProfileInfoReligion",
    components: { InfoVisibilityToggle },
    props: ["visibleReligion", "religion"],
    data: function () {
        return {
            visibleState: this.visibleReligion,
            hasLoaded: false,
        };
    },
    mounted: function () {
        this.hasLoaded = true;
    },
    computed: {
        visible: {
            get() {
                return this.visibleState;
            },

            set(val) {
                this.isLoading = true;
                axios
                    .put("/api/profile/visibility/set", { religion: val })
                    .catch((err) => {
                        console.log(err);
                    })
                    .then((response) => {
                        this.visibleState = val;
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
            },
        },
    },
};
</script>

<style scoped></style>
