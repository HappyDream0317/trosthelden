<template>
    <div class="placeholder_modal_design_dialog_input">
        <div class="placeholder_modal_design_dialog_input_title">
            {{ title }}
        </div>
        <div class="placeholder_modal_design_dialog_input_input">
            <textarea v-model="data"></textarea>
        </div>
    </div>
</template>

<script>
export default {
    name: "DialogInput",
    props: {
        title: String,
        input_text: String,
    },
  data() {
    return {
      data: this.input_text,
    };
  },
  watch: {
    input_text() {
      if (this.data !== this.input_text) {
        this.data = this.input_text;
      }
    },
    data() {
      if (this.data !== this.input_text) {
        this.$emit("update:modelValue", this.data);
      }
    },
  },


};
</script>

<style scoped>
.placeholder_modal_design_dialog_input {
    display: block;
    height: 100px;
    width: 100%;
    position: relative;
}
.placeholder_modal_design_dialog_input_input {
    top: 50%;
    left: 50%;
}
.placeholder_modal_design_dialog_input_title {
}
</style>
