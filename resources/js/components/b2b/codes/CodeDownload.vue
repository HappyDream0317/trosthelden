<template>
  <div class="dropdown">
    <button :disabled="item?.used_at" class="btn btn-link dropdown-toggle code-download__action" type="button"
            :id="`code_download_${item.id}`" data-bs-toggle="dropdown" aria-expanded="false">
      <span
          class="spinner-grow spinner-grow-sm"
          v-if="loading"
          role="status"
          aria-hidden="true"
      ></span>
      <fa-icon v-else icon="play"></fa-icon>
    </button>
    <ul class="dropdown-menu" :aria-labelledby="`code_download_${item.id}`">
      <li>
        <button class="dropdown-item" type="button" @click="generatePDF">Download PDF</button>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "CodeDownload",
  props: {
    item: {
      type: Object,
      default: {}
    },
    userId: {
      type: Number,
    }
  },
  data() {
    return {
      loading: false
    }
  },
  methods: {
    async generatePDF() {
      this.loading = true;

      let {data} = await axios.post(`/api/b2b/${this.userId}/code/${this.item.id}/pdf`, {}, {
        headers: {
          Accept: 'application/pdf',
        }
      });

      if (!data) return false;

      const link = document.createElement('a');
      link.href = data.url;
      link.setAttribute('download', 'sample.pdf');
      document.body.appendChild(link);
      link.click();
      this.loading = false;
    }
  }
}
</script>

<style scoped lang="scss">
.code-download__action.dropdown-toggle::after {
  display: none;
}
</style>