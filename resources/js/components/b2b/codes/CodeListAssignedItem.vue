<template>
  <tr>
    <td>{{ code }}</td>
    <td>
      <code-description :user-id="userId" :item="item"/>
    </td>
    <td>
      {{ assignedAt }}
    </td>
    <td>
      {{ item?.used_at ? 'Ja' : 'Nein' }}
    </td>
    <td class="text-right">
      <code-download :user-id="userId" :item="item"/>
    </td>
  </tr>
</template>

<script>
import CodeDownload from "./CodeDownload.vue";
import CodeDescription from "./CodeDescription.vue";
import CodeIsAssigned from "./CodeIsAssigned.vue";
import moment from "moment/moment";

export default {
  name: "CodeListAssignedItem",
  components: {
    CodeDownload,
    CodeDescription,
    CodeIsAssigned
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
    code() {
      return this.item?.code ? this.item.code.toUpperCase() : '';
    },
    assignedAt() {
      if (!this.item?.assigned_at) return '';
      let ts = new moment(this.item?.assigned_at).local();
      return ts.format("DD.MM.YYYY");
    }
  },
}
</script>

