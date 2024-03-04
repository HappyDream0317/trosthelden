export const StoryblokImageDelivery = {
    computed: {
        imageUrl() {
            if (!this.image) return "";
            const filePath = this.image.filename.replace(
                "https://a.storyblok.com/",
                ""
            );
            return this.image.focus
                ? `https://img2.storyblok.com/800x600/filters:focal(${this.image.focus})/${filePath}`
                : `https://img2.storyblok.com/800x600/${filePath}`;
        },
    },
};
