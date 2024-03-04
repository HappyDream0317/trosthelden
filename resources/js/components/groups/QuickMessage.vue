<template>
    <div class="wrapper rounded">
        <label class="header" for="send_message_to">
            <fa-icon icon="envelope" class="icon"></fa-icon>
            Nachricht senden:
        </label>
        <textarea
            id="send_message_to"
            class="rounded"
            v-model="message"
            :disabled="loading"
        ></textarea>
        <div class="dialog-actions">
            <p class="hint">
                Unter Nachrichten kannst du alle Mitteilungen und Antworten
                einsehen.
            </p>
            <button :disabled="loading" @click="send">Abschicken</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "QuickMessage",
    props: {
        userId: Number,
        recipient: String,
    },
    data() {
        return {
            message: "",
            loading: false,
        };
    },
    methods: {
        send() {
            this.loading = true;
            axios
                .get("/api/chat/info/" + this.userId)
                .then((response) => {
                    this.chatId = response.data.chat_id;
                    return axios
                        .post("/api/chat/" + response.data.chat_id + "/send", {
                            message: this.message,
                        })
                        .then((response) => {
                            this.message = "";
                        });
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    this.loading = false;
                    this.$emit("close");
                });
        },
    },
};
</script>

<style lang="scss" src="../../../sass/dialog.scss" scoped></style>
