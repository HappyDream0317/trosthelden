<template>
  <div class="d-flex">
    <button v-if="loading" type="button" disabled class="btn btn-outline-primary text-primary">
      <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    </button>
    <template v-else>
      <button type="button" :disabled="isAssigned" @click="() => setAssigned(true)" class="btn me-2"
              :class="{'btn-outline-primary': !isAssigned, 'btn-primary': isAssigned }">Ja
      </button>
      <button type="button" :disabled="!isAssigned" @click="() => setAssigned(false)" class="btn"
              :class="{'btn-outline-primary': isAssigned, 'btn-primary': !isAssigned }">Nein
      </button>
    </template>
  </div>
</template>

<script>

export default {
  name: "CodeIsAssigned",
  props: {
    item: {
      type: Object,
      default: {}
    },
    userId: {
      type: Number,
    }
  },
  computed: {
    isAssigned() {
      return !!this.item?.is_assigned;
    }
  },
  data() {
    return {
      loading: false
    }
  },
  methods: {
    setAssigned(value) {
      this.loading = true;
      axios.post(`/api/b2b/${this.userId}/code/${this.item.id}/assigned`, {
        isAssigned: value
      })
          .then((response) => {
            if (response.status === 200 && !response.data.error) {
              this.$eventBus.emit("code-change-assigned", {
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
          });
    },
  },
}
</script>

<style scoped>

</style>