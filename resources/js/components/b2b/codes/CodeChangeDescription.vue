<template>
  <div class="p-4">
    <h2 class="text-center">Name Ihres Kunden</h2>
    <ValidationObserver tag="form" ref="observer" @submit="changeDescription" v-slot="{ meta: {valid, dirty} }">
      <ValidationProvider as="div" name="description" rules="required|max:255" v-model="description" v-slot="{ errorMessage, field }" >
        <div class="form-group">
          <input
              type="text"
              required
              v-bind="field"
              name="description"
              class="form-control"
              :class="{ error: errorMessage }"
              id="description"
          />
          <span v-if="errorMessage" class="validation-error">
                {{ errorMessage }}
            </span>
        </div>
      </ValidationProvider>
      <button
          class="btn btn-primary"
          :disabled="loading || !valid"
          type="submit"
      >
        <span
            class="spinner-grow spinner-grow-sm"
            v-if="loading"
            role="status"
            aria-hidden="true"
        ></span>
        Save
      </button>
    </ValidationObserver>
  </div>
</template>

<script>

export default {
  name: "CodeChangeDescription",
  props: {
    item: {
      type: Object,
      default: {}
    },
    userId: {
      type: Number,
    }
  },
  data() {
    return {
      loading: false,
      description: null,
      error: "",
    };
  },
  mounted() {
    this.description = this.item?.description ?? '';
  },
  methods: {
    changeDescription() {
      this.loading = true;
      axios.post(`/api/b2b/${this.userId}/code/${this.item.id}/description`, {
            'description': this.description
          })
          .then((response) => {
            if (response.status === 200 && !response.data.error) {
              this.$eventBus.emit("code-change-description", {
                userId: this.userId,
                codeId: this.item.id
              });
            }
            if (response.data.error) {
              this.error = response.data.error;
            }
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
}
</script>

<style scoped>

</style>