<template>
    <div class="p-4">
        <h2 class="text-center">Benutzernamen ändern</h2>
        <p></p>
        <form v-on:submit.prevent="changeNickname">
            <ValidationProvider as="div" name="newNickname" rules="required" v-model="newNickname" v-slot="{ errorMessage, field }" >
                <div class="form-group">
                    <label for="newNickname">Neuer Benutzername</label>
                    <input
                        type="text"
                        required
                        v-bind="field"
                        name="newNickname"
                        class="form-control"
                        :class="{ error: errorMessage }"
                        id="newNickname"
                    />
                </div>
                <p class="error-message" v-if="errorMessage">{{ errorMessage }}</p>
            </ValidationProvider>


            <button
                class="btn btn-primary"
                :disabled="!formIsValid || loading"
                type="submit"
            >
                <span
                    class="spinner-grow spinner-grow-sm"
                    v-if="loading"
                    role="status"
                    aria-hidden="true"
                ></span>
                Benutzernamen ändern
            </button>
        </form>
    </div>
</template>

<script>
export default {
    name: "ChangeNickname",
    methods: {
        changeNickname() {
            this.loading = true;
            axios
                .post("/api/settings/change-nickname", {
                    "new-nickname": this.newNickname,
                })
                .then((response) => {
                    console.log(response);
                    if (response.status === 200 && !response.data.error) {
                        this.$router.go();
                    }
                    if (response.data.error) {
                        this.error = response.data.error;
                    }

                    this.loading = false;
                })
                .catch((err) => {
                    console.error(err);
                });
        },
    },
    data() {
        return {
            loading: false,
            newNickname: null,
            error: "",
        };
    },
    computed: {
        formIsValid() {
            if (this.newNickname === null) {
                return false;
            }
            return this.newNickname;
        },
    },
};
</script>

<style scoped lang="scss">
.error {
    color: red;
}

input {
    &.error {
        border: 1px solid red;
    }
}
</style>
