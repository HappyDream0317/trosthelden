<template>
    <div
        class="placeholder_modal_design_action_dialog dialog dialog--reset-partner-request"
    >
        <AcceptDialogWithInput
            v-if="status === 0"
            :dialog="dialog"
            :option="option"
            :input_title="input_title"
            @close="invokeClose"
            :callback="retract"
        ></AcceptDialogWithInput>
        <ChoiceDialogWithInput
            v-else-if="status === 1"
            :dialog="dialog"
            :options="options"
            :input_title="input_title"
            @close="invokeClose"
            :callback="response"
        ></ChoiceDialogWithInput>
        <AcceptDialogWithInput
            v-else-if="status === 2"
            :dialog="dialog"
            :option="option"
            :input_title="input_title"
            @close="invokeClose"
            :callback="remove"
        ></AcceptDialogWithInput>
        <AcceptDialogWithInput
            v-else-if="status === 3"
            :dialog="dialog"
            :option="option"
            :input_title="input_title"
            @close="invokeClose"
            :callback="send"
        ></AcceptDialogWithInput>
    </div>
</template>

<script>
import AcceptDialogWithInput from "../dialog_scheme/AcceptDialogWithInput";
import ChoiceDialogWithInput from "../dialog_scheme/ChoiceDialogWithInput";
import AcceptDialog from "../dialog_scheme/AcceptDialog";
export default {
    name: "DialogActionFriendlist",
    components: { AcceptDialogWithInput, ChoiceDialogWithInput, AcceptDialog },
    props: ["user", "status", "callback"],
    data: function () {
        return {
            chatId: null,
            on_friendlist: this.status,
            hasLoaded: false,
            actions: {
                refuse: this.refuse,
                add: this.add,
                retract: this.retract,
            },
        };
    },
    computed: {
        input_title() {
            var title = "";
            switch (this.status) {
                case 0:
                    title = "Nachricht zum Zurücknehmen der Anfrage";
                    break;
                case 1:
                    title = "Antwortnachricht auf Trauerfreund-Anfrage";
                    break;
                case 2:
                    title = "";
                    break;
                case 3:
                    title = "Nachricht zur Anfrage";
                    break;
                default:
                    break;
            }

            return title;
        },

        option() {
            var option = {};
            switch (this.status) {
                case 0:
                    option = {
                        description:
                            "Trauerfreund-Anfrage " + this.user.nickname + " ",
                        option: "zurückziehen",
                        action: "retract",
                    };
                    break;
                case 2:
                    option = {
                        description:
                            "Ja ich möchte " + this.user.nickname + " ",
                        option: "entfernen",
                        action: "remove",
                    };
                    break;
                case 3:
                    option = {
                        description:
                            "Trauerfreund-Anfrage an " +
                            this.user.nickname +
                            " ",
                        option: "abschicken",
                        action: "",
                    };
                    break;
                default:
                    break;
            }
            //console.log("OPTION");
            //console.log(option);
            return option;
        },

        options() {
            var options = {};
            switch (this.status) {
                case 1:
                    options = {
                        description:
                            "Trauerfreund-Anfrage von " +
                            this.user.nickname +
                            " ",
                        options: {
                            a: {
                                option: "bestätigen",
                                action: "add",
                            },
                            b: {
                                option: "ablehnen",
                                action: "refuse",
                            },
                        },
                    };
                    break;
                default:
                    break;
            }
            return options;
        },

        dialog() {
            var dialog = {};
            switch (this.status) {
                case 0:
                    dialog = {
                        title: "Trauerfreund-Anfrage zurückziehen",
                        icon: "user-slash",
                        description: "Anfrage zurückhziehen Beschreibung",
                    };
                    break;
                case 2:
                    dialog = {
                        title: "Trauerfreund entfernen",
                        icon: "user-slash",
                        description:
                            "Möchten Sie diesen Trauerfreund entfernen?",
                    };
                    break;
                case 1:
                    dialog = {
                        title: "Trauerfreund-Anfrage beantworten",
                        icon: "user-slash",
                        description: "",
                    };
                    break;
                case 3:
                    dialog = {
                        title: "Trauerfreund-Anfrage abschicken",
                        icon: "user-slash",
                        description: "Anfrage abschicken Beschreibung",
                    };
                    break;
                default:
                    break;
            }
            return dialog;
        },
    },
    methods: {
        invokeClose() {
            this.$emit("close");
        },

        response(method, text) {
            //console.log(method);
            this.actions[method](text);
        },
        refuse(text) {
            axios
                .put("/api/user/friend/request/deny/" + this.user.id)
                .then((response) => {
                    if (text) {
                        this.sendChatMessage(text);
                    }
                    this.callback(3);
                    this.$emit("close");
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        add(text) {
            //console.log(this.user);
            axios
                .put("/api/user/friend/request/accept/" + this.user.id)
                .then((response) => {
                    if (text) {
                        this.sendChatMessage(text);
                    }
                    this.callback(2);
                    this.$emit("close");
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        send(text) {
            axios
                .post("/api/user/friend/request/send/" + this.user.id)
                .then((response) => {
                    if (response.data.success) {
                        if (text) {
                            this.sendChatMessage(text);
                        }

                        this.callback(0);
                        this.$emit("close");
                    }
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        sendChatMessage(message) {
            //console.log(message);
            axios
                .get("/api/chat/info/" + this.user.id)
                .then((response) => {
                    this.chatId = response.data.chat_id;
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    axios
                        .post("/api/chat/" + this.chatId + "/send", {
                            message: message,
                            meta: '{"isFriedRequest": true}',
                        })
                        .catch((err) => {
                            console.log(err);
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                });
        },
        answer() {
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
        retract(text) {
            axios
                .put("/api/user/friend/request/retract/" + this.user.id)
                .then((response) => {
                    if (text) {
                        this.sendChatMessage(text);
                    }
                    this.callback(3);
                    this.$emit("close");
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        remove(text) {
            axios
                .put("/api/user/friend/list/remove/" + this.user.id)
                .then((response) => {
                    if (text) {
                        this.sendChatMessage(text);
                    }
                    this.callback(3);
                    this.$emit("close");
                })
                .catch((err) => {
                    console.log(err);
                });
        },

        handleAction(action) {
            this.actions[action]();
        },
    },
};
</script>

<style scoped>
.placeholder_modal_design_action_dialog {
}
</style>
