<template>
  <div class="rounded-box text-center py-3 px-2 mb-2 bg--blue-250">
    <p class="text-white mb-4">Ihr Mitgliedscode ist <br> <b>{{ parner.code }}</b></p>
    <button class="btn btn-primary w-100 a text-uppercase" @click="copyLink" :title="link">
      Link in Zwischenablage kopieren
    </button>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "PartnerCode",
  computed: {
    ...mapGetters("b2bUser", {
      parner: "getPartner",
      redirects: "getRedirects"
    }),
    ...mapGetters("env", {
      getValueFromEnv: "getValueFromEnv"
    }),
    link() {
      const url = this.getValueFromEnv("TROSTHELDEN_URL").value;
      return `${url}?partner=${this.parner.code}`;
    }
  },

  methods: {
    async copyLink() {
        try {
          await navigator.clipboard.writeText(this.link);
        } catch (err) {
        }
    }
  }
}
</script>

<style scoped>

</style>