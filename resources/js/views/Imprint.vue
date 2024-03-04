<template>
  <default-layout>
    <div class="row">
      <div class=" p-3" v-if="content">
        <component
            v-for="el in content.elements"
            :key="el._uid"
            :blok="el"
            :is="el.component"
        ></component>
      </div>
    </div>
  </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import {Storyblok} from "../cms/mixins/storyblok";

export default {
  name: "Imprint",
  components: {DefaultLayout},
  mixins: [Storyblok],
  data() {
    return {
      content: {},
    };
  },
  methods: {
    async storyblokInit() {
      const {story} = await this.fetchStory("recht/impressum");
      this.content = {...story.content};
    },
  },
};
</script>


<style scoped>
.richtext {
  max-width: 100%;
}
</style>

