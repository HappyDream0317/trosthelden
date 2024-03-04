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
import Event from "../../../event.js";
export default {
    name: "DialogActionBlocklist",
    components: { AcceptDialog },
    props: {
        user: Object,
        status: Boolean,
        callback: {
            type: Function,
            default: function () {},
        },
    },

    data: function () {
        return {
            on_blocklist: this.status,
            hasLoaded: false,

            actions: {
                block: this.block,
                unblock: this.unblock,
            },
        };
    },
    mounted: function () {},
    computed: {
        dialog() {
            if (this.on_blocklist) {
                return {
                    title: "Nutzer nicht mehr blockieren",
                    icon: "user-slash",
                    description: "",
                };
            } else {
                return {
                    title: "Nutzer blockieren",
                    icon: "user-slash",
                    description: "",
                };
            }
        },

        option() {
            if (this.on_blocklist) {
                return {
                    description: "Ja ich möchte " + this.user.nickname + " ",
                    option: "nicht mehr blockieren",
                    action: "unblock",
                };
            } else {
                return {
                    description: "Ja ich möchte " + this.user.nickname + " ",
                    option: "blockieren",
                    action: "block",
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

        block() {
            //console.log("block");

            axios
                .post("/api/user/blocklist/" + this.user.id + "/block")
                .then((response) => {
                    this.on_blocklist = true;
                    this.callback();
                    this.$emit("close");
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        unblock() {
            //console.log("unblock");

            axios
                .put("/api/user/blocklist/" + this.user.id + "/unblock")
                .then((response) => {
                    this.on_blocklist = false;
                    this.callback();
                    this.$emit("close");
                })
                .catch((err) => {
                    console.error(err);
                });
        },
    },
};
</script>

<style scoped></style>
