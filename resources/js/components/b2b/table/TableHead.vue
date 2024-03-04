<template>
<thead>
<tr>
  <th  v-for="(column, key) in columns" :key="key" scope="col" :class="[`text-${ column?.align ?? 'left' }`]">
    {{ column?.header ?? ''}}
  </th>
</tr>
</thead>
</template>

<script>
export default {
  name: "CodeListHead",
  props: {
    columns: {
      type: Array,
      default: [],
    },
  }
}
</script>

<style scoped>

</style>