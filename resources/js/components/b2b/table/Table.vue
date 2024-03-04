<template>
  <div>
    <div class="d-flex justify-content-between align-self-center mb-3">
      <h3> {{ title }}</h3>
      <div class="d-flex justify-content-end">
        <slot name="table-actions"></slot>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover table-component">
        <table-head :columns="columns"/>
        <tbody>
        <table-spinner v-if="loading" :columns="columns"/>
        <slot v-else name="rows" :resources="resources"></slot>
        </tbody>
        <table-foot
            :loading="loading"
            :columns="columns"
            :current_page="current_page"
            :last_page="last_page"
            :per_page="per_page"
            :total="total"
            :from="from"
            :to="to"
            @prev="prev"
            @next="next"
        />
      </table>
    </div>
  </div>
</template>

<script>

import TableHead from "./TableHead.vue";
import TableSpinner from "./TableSpinner.vue";
import TableFoot from "./TableFoot.vue";

export default {
  name: "Table",
  components: {
    TableHead,
    TableSpinner,
    TableFoot
  },
  props: {
    title: {
      type: String,
      default: '',
    },
    url: {
      type: String,
      default: '',
    },
    columns: {
      type: Array,
      default: [],
    },
    forceReload: {
      type: Number,
      default: 0,
    }
  },
  data() {
    return {
      resources: [],
      loading: false,
      last_page: 0,
      current_page: 1,
      per_page: 10,
      total: 0,
      from: 0,
      to: 0
    };
  },
  mounted() {
    this.loadList();
  },
  methods: {
    async loadList() {
      this.loading = true;

      let {data} = await axios.get(this.url, {
        params: {
          page: this.current_page, perPage: this.per_page
        }
      });

      if (!data || !data.hasOwnProperty('data') || !Array.isArray(data.data)) {
        this.loading = false;
        return false;
      }

      this.resources = [...(data.data ?? [])];

      this.last_page = data.last_page ?? 0;
      this.total = data.total ?? 0;
      this.from = data.from ?? 0;
      this.to = data.to ?? 0;

      this.loading = false;
    },
    next() {
      this.current_page += 1;
      this.loadList();
    },
    prev() {
      this.current_page -= 1;
      this.loadList();
    },
  },
  watch: {
    forceReload(value) {
      if(value > 0) this.loadList();
    }
  }
}
</script>
<style scoped lang="scss">
.table-component {
  table-layout: fixed;
}
</style>
