<template>
    <div class="card mb-3">
        <div class="card-header">Nachricht schreiben</div>
        <div class="form-group card-body">
            <h5 class="card-title">Nachricht</h5>
            <textarea
                v-validate="'required'"
                v-model="message"
                class="form-control mb-3"
                id="message"
                rows="3"
            ></textarea>
            <span>{{ errors.first("message") }}</span>
            <button @click="submit" type="submit" class="btn btn-primary">
                Senden
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "WritePost",
    props: ["group_id"],
    data: () => ({
        message: null,
        loading: false,
    }),
    methods: {
        submit() {
            this.loading = true;
            axios
                .post("/api/post", {
                    group_id: this.group_id,
                    message: this.message,
                })
                .then((response) => {
                    this.$emit("newPost", response.data);
                })
                .catch((err) => {
                    console.log(err);
                    this.errored = true;
                })
                .finally(() => {
                    this.message = "";
                    this.loading = false;
                });
        },
    },
};
</script>

<style scoped></style>
