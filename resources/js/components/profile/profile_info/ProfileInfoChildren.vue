<template>
    <InfoVisibilityToggle icon="child" v-model="visible" :disable="isLoading">
        Kinder: {{ children }}
        <div
            :children_in_household="children_in_household"
            :children_affected="children_affected"
        ></div>
    </InfoVisibilityToggle>
</template>

<script>
import InfoVisibilityToggle from "./InfoVisibilityToggle";

export default {
    name: "ProfileInfoChildren",
    props: [
        "visibleChildren",
        "number_of_children",
        "children_in_household",
        "children_affected",
    ],
    components: { InfoVisibilityToggle },
    data: function () {
        return {
            isLoading: false,

            visibleState: this.visibleChildren,
        };
    },
    computed: {
        visible: {
            get() {
                return this.visibleState;
            },

            set(val) {
                this.isLoading = true;
                axios
                    .put("/api/profile/visibility/set", { children: val })
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
