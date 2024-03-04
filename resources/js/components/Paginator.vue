<template>
    <ul class="pagination" :class="extraClasses">
        <li v-if="withNumbers" class="pagination-item">
            <button
                type="button"
                @click="onClickFirstPage"
                :disabled="isInFirstPage"
            >
                <fa-icon :icon="firstIcon"></fa-icon>
            </button>
        </li>

        <li class="pagination-item">
            <button
                type="button"
                @click="onClickPreviousPage"
                :disabled="isInFirstPage"
            >
                <fa-icon :icon="prevIcon"></fa-icon>
            </button>
        </li>

        <li v-if="withNumbers" v-for="page in pages" class="pagination-item">
            <button
                type="button"
                @click="onClickPage(page.name)"
                :disabled="page.isDisabled"
                :class="{ active: isPageActive(page.name) }"
            >
                {{ page.name }}
            </button>
        </li>
        <li class="pagination-item">
            <img v-if="imageUrl !== null" :src="imageUrl" />
        </li>
        <li class="pagination-item">
            <button
                type="button"
                @click="onClickNextPage"
                :disabled="isInLastPage"
            >
                <fa-icon :icon="nextIcon"></fa-icon>
            </button>
        </li>

        <li v-if="withNumbers" class="pagination-item">
            <button
                type="button"
                @click="onClickLastPage"
                :disabled="isInLastPage"
            >
                <fa-icon :icon="lastIcon"></fa-icon>
            </button>
        </li>
    </ul>
</template>
<script>
export default {
    name: "paginator",
    props: {
        maxVisibleButtons: {
            type: Number,
            required: false,
            default: 3,
        },
        totalPages: {
            type: Number,
            required: true,
        },
        total: {
            type: Number,
            required: true,
        },
        perPage: {
            type: Number,
            required: true,
        },
        currentPage: {
            type: Number,
            required: true,
        },
        prevIcon: {
            type: String,
            default: "angle-left",
            required: false,
        },
        nextIcon: {
            type: String,
            default: "angle-right",
            required: false,
        },
        firstIcon: {
            type: String,
            default: "angle-double-left",
            required: false,
        },
        lastIcon: {
            type: String,
            default: "angle-double-right",
            required: false,
        },
        withNumbers: {
            type: Boolean,
            default: true,
            required: false,
        },
        imageUrl: {
            type: String,
            default: null,
            required: false,
        },
        extraClasses: {
            type: String,
            default: "",
            required: false,
        },
    },
    computed: {
        startPage() {
            if (this.currentPage === 1) {
                return 1;
            }

            if (this.currentPage === this.totalPages) {
                return this.totalPages - this.maxVisibleButtons + 1;
            }

            return this.currentPage - 1;
        },
        endPage() {
            return Math.min(
                this.startPage + this.maxVisibleButtons - 1,
                this.totalPages
            );
        },
        pages() {
            const range = [];

            for (let i = this.startPage; i <= this.endPage; i += 1) {
                range.push({
                    name: i,
                    isDisabled: i === this.currentPage,
                });
            }

            return range;
        },
        isInFirstPage() {
            return this.currentPage === 1;
        },
        isInLastPage() {
            return this.currentPage === this.totalPages;
        },
    },
    methods: {
        onClickFirstPage() {
            this.$emit("page", 1);
        },
        onClickPreviousPage() {
            this.$emit("page", this.currentPage - 1);
        },
        onClickPage(page) {
            this.$emit("page", page);
        },
        onClickNextPage() {
            this.$emit("page", this.currentPage + 1);
        },
        onClickLastPage() {
            this.$emit("page", this.totalPages);
        },
        isPageActive(page) {
            return this.currentPage === page;
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.pagination {
    list-style-type: none;
}

ul {
    display: flex;
    justify-content: flex-end;
    margin: 0;
    padding: 0;
    list-style: none;
    width: 100%;

    &.center {
        justify-content: center;
        align-content: center;
    }

    &.white {
        li {
            color: white;

            button {
                svg {
                    color: white;
                }
            }
        }
    }

    &.only-arrows-theme {
        li {
            flex: 0 1 45px;
            display: flex !important;
            align-items: center;
            justify-content: center;
            flex-direction: row;

            button {
                display: flex;
                justify-content: center;
                align-content: center;
                flex-direction: row;
                height: 100%;
                padding: 0;

                &:not(:disabled) {
                    &:hover {
                        svg {
                            color: $brand-color-primary;
                        }
                    }
                }

                &:not(:disabled) {
                    svg {
                        font-size: 60px;
                        margin-top: 11px;
                    }
                }

                &:disabled {
                    svg {
                        color: transparent;
                    }
                }
            }
        }

        img {
            height: 75px;
        }
    }

    li.pagination-item {
        padding: 0;
        display: inline-block;

        button {
            border: none;
            background: transparent;
            color: $brand-color-primary;

            &:focus,
            &:active {
                border: none;
                outline: none;
            }

            &[disabled] {
                color: $brand-color-highlight;
            }

            &.active {
                color: $brand-color-primary !important;
                font-weight: 500;
                background: $brand-color-secondary;
            }
        }
    }
}
</style>
