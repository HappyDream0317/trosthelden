<template>
    <InfoVisibilityToggle icon="wrench" v-model="visible">
        {{ job }}
    </InfoVisibilityToggle>
</template>

<script>
import InfoVisibilityToggle from "./InfoVisibilityToggle";

export default {
    name: "ProfileInfoJob",
    components: { InfoVisibilityToggle },
    props: ["job", "visibleJob"],
    data: function () {
        return {
            visibleState: this.visibleJob,
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
                    .put("/api/profile/visibility/set", { job: val })
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
