<template>
  <div
      v-if="show"
      class="impressum"
      :id="id"
      :class="className"
      v-html="richtext"
  ></div>
</template>

<script>
import Storyblok from "storyblok-js-client";
import {mapGetters} from "vuex";

const Api = new Storyblok({region: "de"});

export default {
  name: "impressum",
  props: ["blok"],
  data() {
    return {
      id: "",
      content: "",
      className: "",
    };
  },
  mounted() {
    this.id = this.blok.id;
    this.content = this.blok.text.content;
    this.className = this.blok.className;
  },
  computed: {
    show() {
      return (
          this.blok.destination === this.destination ||
          this.blok.destination === "global"
      );
    },
    richtext() {
      return this.blok.text
          ? Api.richTextResolver.render(this.blok.text)
          : "";
    },
    ...mapGetters("storyblok", {
      destination: "destination",
    }),
  },
};
</script>

<style lang="scss">
.impressum {
}
</style>
