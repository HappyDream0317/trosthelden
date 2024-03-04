<template>
    <AcceptDialog
        :dialog="dialog"
        :option="option"
        @close="invokeClose"
        @action="handleDialogAction"
    ></AcceptDialog>
</template>

<script>
import AcceptDialog from "../dialog_scheme/AcceptDialog";
export default {
    name: "DialogActionWatchlist",
    components: { AcceptDialog },
    props: ["user", "status", "callback"],

    data: function () {
        return {
            on_watchlist: this.status,
            hasLoaded: false,

            actions: {
                watch: this.watch,
                remove: this.remove,
            },
        };
    },
    mounted: function () {},
    computed: {
        dialog() {
            if (this.on_watchlist) {
                return {
                    title: "Von Merkliste entfernen",
                    icon: "user",
                    description: "",
                };
            } else {
                return {
                    title: "Der Merkliste hinzufügen",
                    icon: "star",
                    description: "",
                };
            }
        },

        option() {
            if (this.on_watchlist) {
                return {
                    description:
                        "Die Person " +
                        this.user.nickname +
                        " von deiner Merkliste ",
                    option: "entfernen",
                    action: "remove",
                };
            } else {
                return {
                    description:
                        "Die Person " +
                        this.user.nickname +
                        " deiner Merkliste",
                    option: "hinzufügen",
                    action: "watch",
                };
            }
        },
    },
    methods: {
        invokeClose() {
            this.$emit("close");
        },

        handleDialogAction(action) {
            //console.log(action);
            this.actions[action]();
        },

        watch() {
            //console.log("watch");

            axios
                .put("/api/user/watchlist/watch/" + this.user.id)
                .then((response) => {
                    this.on_watchlist = true;
                    this.callback();
                    this.$emit("close");
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        remove() {
            //console.log("remove");

            axios
                .put("/api/user/watchlist/remove/" + this.user.id)
                .then((response) => {
                    this.on_watchlist = false;
                    this.callback();
                    this.$emit("close");
                })
                .catch((err) => {
                    console.log(err);
                });
        },
    },
};
</script>

<style scoped></style>
