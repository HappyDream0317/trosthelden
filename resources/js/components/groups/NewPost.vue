<template>
    <div class="write-new-post rounded" id="neuer_beitrag">
        <div class="input-wrapper">
            <div class="input-heading" v-if="isNewPost">
                <span>Beitrag verfassen</span><br />
                <span
                    >Du möchtest einen eigenen Beitrag verfassen? Hier kannst du
                    gleich loslegen.</span
                >

                <input
                    v-model="title.value"
                    id="title"
                    type="text"
                    class="form-control"
                    name="title"
                    placeholder="Deine Überschrift"
                />

                <div class="error-message" v-if="false === title.valid">
                    {{ title.errorMessage }}
                </div>

                <span class="hint"
                    >Tipp: Je treffender und interessanter deine Überschrift
                    ist, desto mehr Antworten wirst du bekommen.</span
                >
            </div>
            <div class="menubar">
                    <button
                        class="menubar__button btn--undo"
                        @click="comment.commands.undo"
                    >
                        <fa-icon icon="undo" />
                    </button>

                    <button
                        class="menubar__button btn--redo"
                        @click="comment.commands.redo"
                    >
                        <fa-icon icon="redo" />
                    </button>
                    <button
                        class="menubar__button btn--bold"
                        :class="{ 'is-active': comment.isActive.bold() }"
                        @click="comment.commands.bold"
                    >
                        <fa-icon icon="bold" />
                    </button>

                    <button
                        class="menubar__button btn--italic"
                        :class="{ 'is-active': comment.isActive.italic() }"
                        @click="comment.commands.italic"
                    >
                        <fa-icon icon="italic" />
                    </button>

                    <button
                        class="menubar__button btn--underline"
                        :class="{ 'is-active': comment.isActive.underline() }"
                        @click="comment.commands.underline"
                    >
                        <fa-icon icon="underline" />
                    </button>

                    <button
                        class="menubar__button btn--ul"
                        :class="{ 'is-active': comment.isActive.bullet_list() }"
                        @click="comment.commands.bullet_list"
                    >
                        <fa-icon icon="list-ul" />
                    </button>

                    <button
                        class="menubar__button btn--ol"
                        :class="{ 'is-active': comment.isActive.ordered_list() }"
                        @click="comment.commands.ordered_list"
                    >
                        <fa-icon icon="list-ol" />
                    </button>
            </div>

            <editor-content
                class="comment"
                :editor="comment"
                :disabled="disabled"
            />
            <span class="error-message" v-if="false === message.valid">
                {{ message.errorMessage }}
            </span>

            <div class="submit-bar">
                <button class="btn btn-primary" @click="submit">
                    Veröffentlichen
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import UserInfo from "./UserInfo";
import { Editor, EditorContent } from '@tiptap/vue-3'
import CodeBlock from "@tiptap/extension-code-block";
import HardBreak from "@tiptap/extension-hard-break";
import Heading from "@tiptap/extension-heading";
import HorizontalRule from "@tiptap/extension-horizontal-rule";
import OrderedList from "@tiptap/extension-ordered-list";
import BulletList from "@tiptap/extension-bullet-list";
import ListItem from "@tiptap/extension-list-item";
import TaskItem from "@tiptap/extension-task-item";
import TaskList from "@tiptap/extension-task-list";
import Bold from "@tiptap/extension-bold";
import Code from "@tiptap/extension-code";
import Italic from "@tiptap/extension-italic";
import Link from "@tiptap/extension-link";
import Strike from "@tiptap/extension-strike";
import Underline from "@tiptap/extension-underline";
import History from "@tiptap/extension-history";
import Blockquote from "@tiptap/extension-blockquote";
import moment from "moment";
import { mapGetters } from "vuex";

export default {
    name: "NewPost",
    components: { UserInfo, EditorContent },
    props: {
        targetType: String,
        targetId: Number,
        postId: Number,
        quote: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            loading: false,
            message: {
                valid: true,
                errorMessage:
                    "Bitte gib eine Nachricht in das Kommentarfeld ein.",
            },
            title: {
                value: "",
                valid: true,
                errorMessage:
                    "Bitte gib deinem Beitrag noch eine Überschrift, sodass dieser von von anderen Nutzer gefunden werden kann.",
            },
            emptyDocument: "<p></p>",
            comment: new Editor({
                content: "",
                extensions: [
                    Blockquote,
                    BulletList,
                    CodeBlock,
                    HardBreak,
                    Heading.configure({ levels: [1, 2, 3] }),
                    HorizontalRule,
                    ListItem,
                    OrderedList,
                    TaskItem,
                    TaskList,
                    Link,
                    Bold,
                    Code,
                    Italic,
                    Strike,
                    Underline,
                    History,
                ],

                onUpdate: ({ state, getHTML, getJSON, transaction }) => {
                    const contentJson = getJSON();
                    if (
                        contentJson.content.length === 1 &&
                        contentJson.content[0].type === "blockquote"
                    ) {
                        this.comment.setContent(this.emptyDocument);
                    }
                },
            }),
        };
    },
    watch: {
        quote: function (quote, oldQuote) {
            this.comment.setContent(this.getQuoteLayout());
            this.comment.focus("end");
        },
    },
    mounted() {
        this.$eventBus.on("new-post-focus", (event) => {
            this.focus();
        });
    },
    computed: {
        isNewPost() {
            return this.targetType === "post";
        },
        disabled() {
            return this.loading;
        },
        ...mapGetters("currentUser", {
            isPremium: "isPremium",
        }),
    },
    beforeDestroy() {
        // Always destroy your editor instance when it's no longer needed
        this.comment.destroy();
    },
    methods: {
        getQuoteLayout() {
            const momentDate = new moment(this.quote.date);
            const userInfo = this.getUserIdentification(this.quote.user);
            const date = momentDate.format("DD.MM.YYYY HH:mm");
            const comment = this.quote.comment;

            let quoteHTML =
                `<blockquote><p>Zitat von: <b>` +
                userInfo +
                `</b> ` +
                date +
                `</p>` +
                comment +
                `</blockquote>`;
            quoteHTML += `<p></p>`;

            return quoteHTML;
        },
        getData() {
            if (this.isNewPost) {
                return {
                    group_id: this.targetId,
                    title: this.title.value,
                    message: this.comment.getHTML(),
                };
            }
            return {
                comment: this.comment.getHTML(),
                comment_parent: this.targetId,
            };
        },
        getUrl() {
            if (this.isNewPost) {
                return "/api/post";
            }
            return `/api/post/${this.postId}/comments`;
        },
        validate() {
            this.message.valid = true;
            this.title.valid = true;

            if (!this.postId) {
                // this is a new post
                this.validateMessage();
                this.validateTitle();
                return this.message.valid && this.title.valid;
            } else {
                this.validateMessage();
                return this.message.valid;
            }
        },
        validateMessage() {
            const messageHtml = this.comment.getHTML();
            if (
                messageHtml === this.emptyDocument ||
                this.strip(messageHtml).trim() === ""
            ) {
                this.message.valid = false;
            } else {
                this.message.valid = true;
            }
        },
        validateTitle() {
            if (this.title.value === "") {
                this.title.valid = false;
            }
        },
        strip(text) {
            return text.replace(/(<([^>]+)>)/gi, "");
        },
        submit() {
            if (false === this.validate()) {
                return;
            }
            if (!this.isPremium) {
                this.$router.push({ name: "premium" });
            }
            if (this.loading) {
                return;
            }

            this.loading = true;

            const data = this.getData();
            const url = this.getUrl();
            axios
                .post(url, data)
                .then((response) => {
                    this.$emit("newPost", response.data);

                    if (this.isNewPost) {
                        this.$store.dispatch("matomo/postPublished");
                    } else {
                        this.$store.dispatch("matomo/commentPublished");
                    }
                })
                .catch((err) => {
                    console.log(err);
                })
                .finally(() => {
                    this.comment.setContent(this.emptyDocument);
                    this.title.value = "";
                    this.loading = false;
                });
        },
        getUserIdentification(user) {
            let identifier = [user.nickname];
            if (user.sex) {
                identifier.push(user.sex);
            }
            if (user.age) {
                identifier.push(user.age);
            }
            return identifier.join(" / ");
        },
        focus(position = "end") {
            this.comment.focus(position);
        },
        beforeDestroy() {
            this.$eventBus.off("new-post-focus");
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.write-new-post {
    background-color: $brand-color-base;
    padding: 0.625rem;
    display: flex;
}

.input-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;

    & > .input-heading > span:first-child {
        color: $brand-color-primary;
        font-size: $h2-font-size;
        font-weight: 500;
    }

    & .hint {
        color: $brand-color-primary;
    }

    & [type="text"],
    & textarea,
    .textarea {
        display: block;
        width: 100%;
        padding: 0.2rem;
    }

    & .submit-bar {
        padding: 0.625rem;
        text-align: center;
    }
}

.menubar {
    background: $primary;
    border-top-right-radius: 0.5rem;
    border-top-left-radius: 0.5rem;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-items: center;
    padding: 5px;

    &__button {
        background: none;
        color: $white;
        border: none;
        padding: 5px 9px;
        @media only screen and (min-width: 768px) {
            margin: 0 5px;
            padding: 5px;
        }

        &.is-active {
            color: $dark-blue;
        }

        &.btn--redo,
        &.btn--underline {
            border-right: 1px solid white;

            @media only screen and (min-width: 768px) {
                padding-right: 20px;
                margin-right: 0;
            }
        }
    }
}
</style>
