<template>
<tfoot>
<tr>
  <td class="text-left align-middle" :colspan="colspan">
    <span class="h6">{{from}} - {{to}} of {{total}}</span>
  </td>
  <td class="text-right">
    <div class="btn-group" role="group" >
      <button type="button" class="btn btn-link" @click="() => emit('prev')" :disabled="loading || current_page <= 1">
        <fa-icon size="2x" icon="chevron-circle-left"></fa-icon>
      </button>
      <button type="button" class="btn btn-link" @click="() => emit('next') " :disabled="loading || last_page === current_page">
        <fa-icon size="2x" icon="chevron-circle-right"></fa-icon>
      </button>
    </div>
  </td>
</tr>
</tfoot>
</template>

<script>
export default {
  name: "TableFoot",
  emmit: ["prev", "next"],
  props: {
    columns: {
      type: Array,
      default: [],
    },
    loading: {
      type: Boolean,
      default: false,
    },
    last_page: {
      type: Number,
      default: 0,
    },
    current_page: {
      type: Number,
      default: 1,
    },
    per_page: {
      type: Number,
      default: 0,
    },
    total: {
      type: Number,
      default: 0,
    },
    from: {
      type: Number,
      default: 0,
    },
    to: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    colspan() {
      return (this.columns?.length ?? 0) - 1;
    }
  },
  methods: {
    emit(value) {
      this.$emit(value);
    },
  }
}
</script>

<style scoped>

</style>