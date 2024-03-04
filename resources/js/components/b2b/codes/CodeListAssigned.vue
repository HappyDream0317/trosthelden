<template>
  <Table :url="url" :forceReload="forceReload" :columns="columns" title="Vergebene Gutscheine">
    <template v-slot:rows="{ resources }">
      <code-list-assigned-item  v-for="(item, key) in resources"
                      :key="key"
                      :item="item"
                      :userId="currentUserId"
      />
    </template>
  </Table>
</template>

<script>

import Table from "../table/Table.vue"
import CodeAddFlatrate from "./CodeAddFlatrate.vue";
import CodeListAssignedItem from "./CodeListAssignedItem.vue";
import {mapGetters} from "vuex";

export default {
  name: "CodeListAssigned",
  components: {
    CodeAddFlatrate,
    CodeListAssignedItem,
    Table,
  },
  data() {
    return {
      forceReload: 0,
      columns: [
        {
          header: 'Code',
        },
        {
          header: 'Name Ihres Kunden',
        },
        {
          header: 'Vergeben am',
        },
        {
          header: 'Eingelöst?',
        },
        {
          header: 'Download Gutschein',
          align: 'right'
        },
      ]
    };
  },
  computed: {
    ...mapGetters("currentUser", {
      currentUserId: "getId"
    }),
    url() {
      return `/api/b2b/${this.currentUserId}/code/assigned`;
    }
  },
  mounted() {
    this.$eventBus.on("code-change-description", () => this.reload());
    this.$eventBus.on("code-change-assigned", () => this.reload());
  },
  methods: {
    reload() {
      this.forceReload += 1;
    }
  }
}
</script>
