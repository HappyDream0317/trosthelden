<template>
    <div class="d-flex mb-1" @click="toggle">
        <div class="text-center w-25px">
            <fa-icon :fixed-width="true" class="fa-w-16" :icon="icon" />
        </div>
        <div class="ps-3"><slot /></div>
        <div class="ms-auto">
            <fa-icon
                ref="icon_job_toggle_visibility"
                :icon="visibilityIcon"
                class="me-2 mt-1 color--grey"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: "InfoVisibilityToggle",
    props: ["modelValue", "icon", "disable"],
    data() {
      return {
        data: this.modelValue,
      };
    },
    computed: {
        visibilityIcon() {
            if (this.data) {
                return "eye";
            }
            return "eye-slash";
        },
    },
    methods: {
        toggle() {
            if (!this.disable) {
                this.data = !this.data;
            }
        },
    },
    watch: {
      modelValue() {
        if (this.data !== this.modelValue) {
          this.data = this.modelValue;
        }
      },
      data() {
        if (this.data !== this.modelValue) {
          this.$emit("update:modelValue", this.data);
        }
      },
    },
};
</script>

<style scoped>
.color--grey {
    color: #aaa !important;
}
</style>
