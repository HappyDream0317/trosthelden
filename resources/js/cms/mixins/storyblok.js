import StoryblokClient from "storyblok-js-client";
import {mapGetters} from "vuex";

export const Storyblok = {
    data() {
        return {
            client: null,
            token: null,
            version: null,
        };
    },
    computed: {
        ...mapGetters("env", {
            getValueFromEnv: "getValueFromEnv",
            isInit: "isInit"
        }),
    },
    methods: {
        async envInit() {
            if (!this.isInit) {
                await this.$store.dispatch("env/init");
            }
            this.token = await this.getValueFromEnv("STORYBLOK_TOKEN").value;
            this.version = await this.getValueFromEnv("STORYBLOK_VERSION").value;
            this.initClient();
        },
        initClient() {
            this.client = new StoryblokClient({
                accessToken: this.token,
            });
            window.storyblok.init({
                accessToken: this.token,
            });
            window.storyblok.on("change", () => {
                this.fetchStory(this.$route.path, "draft");
            });
            window.storyblok.pingEditor(() => {
                if (window.storyblok.isInEditor()) {
                    this.$store.dispatch("storyblok/setIsInEditor", true);
                    this.version = "draft";
                }
                this.storyblokInit(this.$route.path);
            });

        },
        fetchStory(slug) {
            return this.client
                .get("cdn/stories/" + slug, {
                    version: this.version,
                })
                .then(({data}) => data)
                .catch((error) => {
                    console.error(error);
                });
        },
        fetchStories(startsWith) {
            return this.client
                .get(`cdn/stories/?starts_with=${startsWith}`, {
                    sort_by: "first_published_at:desc",
                    version: this.version,
                    per_page: 100,
                })
                .then(({data}) => data)
                .catch((error) => {
                    console.error(error);
                });
        },
        createRichtext(text) {
            return text ? this.client.richTextResolver.render(text) : "";
        },
    },
    created() {
        this.envInit();
    },
    watch: {
        $route(to) {
            if (window.storyblok.isInEditor()) {
                this.version = "draft";
            }
            this.storyblokInit(this.$route.path);
        },
    },
};
