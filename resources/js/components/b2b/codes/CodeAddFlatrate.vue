<template>
  <button v-if="!disable" :disabled="loading" class="btn btn-primary" @click="addNew">Add New Code</button>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "CodeAddFlatrate",
  props: {
    loading: {
      type: Boolean,
      default: false
    }
  },

  computed:{
    ...mapGetters("currentUser", {
      currentUserId: "getId"
    }),
    ...mapGetters("b2bUser", {
      couponId: "getFlatrateCoupon"
    }),
    disable() {
      return !this.couponId;
    }
  },
  methods: {
    async addNew() {

      try {
        let {data} = await axios.post(`/api/b2b/${this.currentUserId}/coupon/${this.couponId}/code`);

        if(!data?.id) Promise.reject();

        this.$eventBus.emit("new-code-flareate");

      } catch (e) {

      }
    }
  }
}
</script>

<style scoped>

</style>