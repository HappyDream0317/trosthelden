<template>
  <Table :url="url" :forceReload="forceReload" :columns="columns" title="Offene Gutscheine">
    <template  v-slot:table-actions>
      <code-add-flatrate v-if="hasFlatrate" />
    </template>
    <template v-slot:rows="{ resources }">
      <code-list-open-item  v-for="(item, key) in resources"
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
import CodeListOpenItem from "./CodeListOpenItem.vue";
import {mapGetters} from "vuex";

export default {
  name: "CodeListOpen",
  components: {
    CodeAddFlatrate,
    CodeListOpenItem,
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
          header: 'Code vergeben?',
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
    ...mapGetters("b2bUser", {
      hasFlatrate: "getHasFlatrate"
    }),
    url() {
      return `/api/b2b/${this.currentUserId}/code/not-assigned`;
    }
  },
  mounted() {
    this.$eventBus.on("code-change-assigned", () => this.reload());
    this.$eventBus.on("code-change-description", () => this.reload());
    this.$eventBus.on("new-code-flareate", () => this.reload());
  },
  methods: {
    reload() {
      this.forceReload += 1;
    }
  }
}
</script>
