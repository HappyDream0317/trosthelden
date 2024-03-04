<template>
    <div class="row">
        <ul>
            <li v-for="highlight in highlights">{{ highlight.text }}</li>
        </ul>

        <button class="btn btn-primary">Trosthelden Premium CTA</button>
    </div>
</template>

<script>
export default {
    name: "premium-cta",
    props: ["blok"],
    data() {
        return {
            id: "",
            highlights: "",
            className: "",
        };
    },
    mounted() {
        // console.log(this.blok);
        this.id = this.blok.id;
        this.highlights = this.blok.highlights;
        this.className = this.blok.className;
    },
};
</script>

<style scoped>
img {
    max-width: 630px;
}
</style>
