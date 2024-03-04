<template>
  <div class="d-flex justify-content-between">
    <button class="btn btn-link" type="button" @click="openCodeChangeDescripttion">
      <fa-icon icon="edit" class="color--primary me-2" ></fa-icon>
      {{ description }}
    </button>
  </div>
</template>

<script>
import CodeChangeDescription from "./CodeChangeDescription.vue"
export default {
  name: "CodeDescription",
  components: {
    CodeChangeDescription
  },
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
    description() {
      let text = this.item.description;
      if(!text)
        return '...';
      else if(text.length > 15)
        return text.substring(0, 15) + "...";
      else
        return text;
    }
  },
  methods: {
    async openCodeChangeDescripttion() {
      this.$eventBus.emit("modal-requested", {
        component: CodeChangeDescription,
        props: {
          item: this.item,
          userId: this.userId,
        }
      });
    }
  }
}
</script>
