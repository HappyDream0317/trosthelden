<template>
  <tr>
    <td>{{ code }}</td>
    <td>
      <code-description :user-id="userId" :item="item" />
    </td>
    <td>
      <code-is-assigned v-if="!item.is_assigned" :user-id="userId" :item="item" />
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

export default {
  name: "CodeListOpenItem",
  components:{
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
  computed:{
    code() {
      return this.item?.code ? this.item.code.toUpperCase() : '';
    }
  },
}
</script>

