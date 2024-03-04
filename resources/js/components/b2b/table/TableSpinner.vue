<template>
  <tr>
    <td :colspan="colspan">
      <div class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
          <span class="visually-hidden-focusable">Loading...</span>
        </div>
      </div>
    </td>
  </tr>
</template>

<script>
export default {
  name: "TableSpinner",
  props: {
    columns: {
      type: Array,
      default: [],
    },
  },
  computed: {
    colspan() {
      return (this.columns?.length ?? 0);
    }
  }
}
</script>

<style scoped>

</style>