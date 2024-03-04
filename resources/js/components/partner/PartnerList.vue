<template>
    <div class="list-wrapper">
        <h2 v-if="title">
            {{ title }}
        </h2>
        <template v-if="!isFirstView">
            <div v-if="resources.length > 0">
                <div v-if="introMessage" class="mb-4">
                    {{ introMessage }}
                </div>
                <partner-list-item
                    v-for="resource in resources"
                    :key="resource.id"
                    :resource="resource"
                    :partnerAttrName="partnerAttrName"
                />
                <div class="text-center">
                    <button
                        v-if="lastPage !== currentPage && !loading"
                        @click="loadMore"
                        type="button"
                        class="btn btn-primary"
                    >
                        Mehr anzeigen
                    </button>
                    <span
                        v-if="
                            lastPage === currentPage &&
                            showLastPageMessageHint &&
                            !loading
                        "
                        >Alle Einträge wurden geladen.</span
                    >
                </div>
            </div>
            <template v-else-if="emptyMessage && !loading">
              <slot name="emptyMessage" />
            </template>
        </template>
        <template v-else>
            <partner-list-countdown
                v-if="!customMessage"
                :countdown="countdown"
            />
            <div v-else-if="customMessage">{{ customMessage }}</div>
        </template>
        <template v-if="loading">
            <div
                class="d-flex justify-content-center align-items-center p-2 text-center"
            >
                <span
                    class="spinner-border spinner-border-sm me-4"
                    role="status"
                    aria-hidden="true"
                ></span>
                <span>Lädt...</span>
            </div>
        </template>
    </div>
</template>

<script>
import Paginator from "../Paginator";
import PartnerListItem from "./PartnerListItem";
import PartnerListCountdown from "../PartnerListCountdown";
import { mapGetters } from "vuex";

export default {
    name: "PartnerList",
    components: { PartnerListItem, Paginator, PartnerListCountdown },
    props: {
        id: {
            type: String,
            default: "matchings_list",
        },
        title: {
            type: String,
            default: null,
        },
        emptyMessage: {
            type: Boolean,
            default: false,
        },
        introMessage: {
            type: String,
            default: null,
        },
        resourceUrl: {
            type: String,
            default: "/api/user/matching",
        },
        useResources: {
            type: Array,
            default: null,
        },
        partnerAttrName: {
            type: String,
            default: "partner",
        },
        newAttrName: {
            type: String,
            default: "first_display",
        },
        perPage: {
            type: Number,
            default: 15,
        },
        showPagination: {
            type: Boolean,
            default: true,
        },
        showLastPageMessageHint: {
            type: Boolean,
            default: true,
        },
        customMessage: {
            type: String,
            default: "",
        },
    },
    data() {
        return {
            resources: this.useResources ?? [],
            loading: false,
            lastPage: 0,
            currentPage: 0,
            countdown: 0,
            loadPerData: false
        };
    },
    methods: {
        updateResources(resources) {
            if (Array.isArray(resources)) {
                this.resources = _.concat(this.resources, resources);
            } else {
                this.resources.unshift(resources);
            }
        },
        getData(params) {
            return axios.get(this.resourceUrl, {
                params
            });
        },
        async handlerData(results) {
            if (this.loadPerData) {
                this.loadPerData = false;
                await this.getData({
                    page: 1,
                    perPage: (this.currentPage * this.perPage)
                }).then(({ data }) => {
                    this.updateResources(data.data);
                });
            } else {
                this.updateResources(results);
            }
        },
        loadList() {
            return this.getData({
                page: this.currentPage,
                perPage: this.perPage
            }).then(async ({ data }) => {
                
                    await this.handlerData(data.data);                    

                    let lastPage = parseInt(data.last_page);
                    let currentPage = parseInt(data.current_page);

                    let total = parseInt(data.total);

                    if (this.useResources !== null) {
                        lastPage = total / this.perPage;
                    }

                    this.lastPage = lastPage;
                    this.currentPage = currentPage;
                    
                    this.scrollToPosition();
                })
                .catch((error) => {
                    console.error(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        loadMore() {
            return new Promise((resolve) => {
                if (this.currentPage <= this.lastPage || this.lastPage === 0) {
                    this.loading = true;
                    this.currentPage += 1;
                    localStorage.setItem(`partner_list_${this.id}_currentPage`, this.currentPage);

                    this.loadList();
                    resolve();
                }
            });
        },
        scrollToPosition() {
            const coordinates = this.fetchCurrentScrollPosition();
            if (coordinates) {
                setTimeout(() => {
                    window.scrollTo(coordinates.x, coordinates.y);
                    localStorage.removeItem("matchingsScrollPosition");
                }, 1000);
            }
        },
        async setPageFromLocalStorage() {
                let currentPage = await this.fetchCurrentPageFromLocalStorage();
                let loadPerData = (currentPage > 1);

                this.loadPerData = loadPerData;
                this.currentPage = (currentPage - 1);

        },
        fetchCurrentPageFromLocalStorage() {
            let currentPage = localStorage.getItem(`partner_list_${this.id}_currentPage`);
            return currentPage ? JSON.parse(currentPage) : 1;
        },
        fetchCurrentScrollPosition() {
            const coordinates = localStorage.getItem("matchingsScrollPosition");
            return coordinates ? JSON.parse(coordinates) : null;
        },
    },
    watch: {
        isFirstView: {
            handler: function (value, oldValue) {
                if (!value && oldValue) this.loadList();
            },
        },
    },
    computed: {
        ...mapGetters("currentUser", {
            isFirstView: "isFirstView",
        }),
    },
    async mounted() {
        await this.setPageFromLocalStorage();
        if (!this.useResources) {
            if (localStorage.getItem("firstView")) {
                this.$store.dispatch("currentUser/firstViewCounterInit");
            } else {
                await this.loadMore();
                this.$eventBus.emit("fetch-new-matches-count");
            }
        }
    },
};
</script>

<style scoped></style>
