<template>
    <div class="d-flex flex-column flex-lg-row p-3" :key="changed">
        <div class="me-lg-4 mb-2 mb-lg-0">
            <label class="h4 font-weight-normal">Dein Profilbild</label>
            <div class="profile--image position-relative">
                <img
                    v-if="avatar"
                    class="avatar"
                    :src="avatar"
                    alt="Dein Profilbild"
                />
                <AvatarPlaceholder class="avatar" v-else></AvatarPlaceholder>
                <div
                    v-on:click="deleteImage"
                    v-bind:class="{ hidden: !avatar }"
                >
                    <span class="deleteHandler">Profilbild entfernen</span>
                </div>
                <div class="icon--edit-bottom">
                    <span
                        ref="btn_image_upload"
                        v-on:click="openFileUpload"
                        class="pencil-edit-button"
                    >
                        <fa-icon
                            ref="icon_image_edit"
                            icon="pencil-alt"
                        ></fa-icon>
                    </span>
                </div>
            </div>
        </div>
        <div class="flex-grow-0 flex-lg-grow-1">
            <div class="icon--info tooltip">
                <fa-icon icon="info-circle" class="me-2">
                    <span class="tooltiptext">{{ tooltip_text }}</span>
                </fa-icon>
            </div>
            <label
                class="h4 font-weight-normal profile-label--center"
                for="profile_slogan"
            >
                Dein persönlicher Spruch
                <span
                    v-on:click="edit"
                    v-if="!editMotto"
                    class="icon--edit-direct"
                >
                    <span class="pencil-edit-button" @click="editMotto = true">
                        <fa-icon icon="pencil-alt" />
                    </span>
                </span>
            </label>
            <div>
                <ValidationProvider as="div" rules="max:250" v-model="motto" v-slot="{ errorMessage, field }" :validateOnModelUpdate="false" :validateOnInput="true">
                    <textarea
                        id="profile_slogan"
                        class="rounded w-100 textarea p-2 border-0"
                        ref="input_motto"
                        v-bind="field"
                        v-on:click="edit"
                        type="text"
                        rows="3"
                        placeholder="Hast du einen Lieblingsspruch? Welche Worte haben dir geholfen? Lass die anderen daran teilhaben!"
                        :disabled="!editMotto"
                    ></textarea>

                    <div v-if="editMotto" class="profile-edit-actions">
                        <button
                            @click="save"
                            class="btn btn-primary btn-save"
                            :disabled="isDisabled"
                        >
                            <fa-icon icon="save"></fa-icon>
                            Speichern
                        </button>
                        <button
                            @click="cancel"
                            class="btn btn-outline-primary btn-edit-cancel"
                        >
                            Abbrechen
                        </button>
                    </div>

                    <span class="validation-error">{{ errorMessage }}</span>
                </ValidationProvider>
            </div>
        </div>
        <ProfileAvatarUpload />
    </div>
</template>

<script>
import AvatarPlaceholder from "../AvatarPlaceholder";
import ImageUploadFailedPopUp from "../popups/ImageUploadFailedPopUp";
import ProfileAvatarUpload from "./ProfileAvatarUpload";
import AbstractModal from "../action_icons/AbstractModal";
import {mapGetters} from "vuex";

export default {
    name: "ProfilePortrait",
    props: ["tooltip_text", "portrait"],
    components: { AbstractModal, AvatarPlaceholder, ProfileAvatarUpload },
    data: function () {
        return {
            motto: this.portrait.motto,
            motto_progress: 0,
            image_progress: 0,
            editMotto: false,
            prevMotto: "",
            appUrl: null,
            changed: 0,
        };
    },
    mounted: function () {

        this.appUrl = window.location.origin;

        if (this.portrait.avatar) {
            this.image_progress = 10;
        }
        if (this.portrait.motto) {
            this.motto_progress = 20;
        }
        this.$emit("input", this.motto_progress + this.image_progress);
        this.$eventBus.on("avatar-uploaded", (data) => {
            this.portrait.avatar = this.appUrl + `/${data.avatarUrl}`;
        });

        if (
            navigator.platform.match("iPhone") ||
            navigator.platform.match("iPod") ||
            navigator.platform.match("iPad") ||
            navigator.platform.match("Mac") ||
            navigator.platform.match("Pike")
        ) {
            let css =
                    ".textarea, textarea.textarea:disabled { color: #121212; -webkit-text-fill-color: #121212; -webkit-opacity: 1, opacity:1 }; .textarea, textarea.textarea::placeholder {color: #121212; -webkit-text-fill-color: #121212; -webkit-opacity: 1; opacity:1 }",
                head =
                    document.head || document.getElementsByTagName("head")[0],
                style = document.createElement("style");

            head.appendChild(style);

            style.type = "text/css";
            if (style.styleSheet) {
                // This is required for IE8 and below.
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }
        }
    },
    computed: {
        isDisabled() {
            return this.motto.length > 250;
        },
        avatar: {
            get: function () {
                if (
                    this.portrait.avatar !== null &&
                    this.portrait.avatar !== ""
                ) {
                    if (this.portrait.avatar.includes(this.appUrl)) {
                        return this.portrait.avatar;
                    } else {
                        return this.appUrl + `/${this.portrait.avatar}`;
                    }
                }
                return "";
            },
            set: function (newValue) {
                //console.log(newValue);
            },
        },
    },
    methods: {
        edit() {
            this.prevMotto = this.motto;
        },
        cancel() {
            this.motto = this.prevMotto;
            this.editMotto = false;
        },
        save() {
            if (this.motto && this.motto.trim() === "") {
                this.motto_progress = 0;
            } else {
                this.motto_progress = 20;
            }

            axios
                .put("/api/profile/motto/saveMotto", {
                    motto: this.motto,
                })
                .then(() => {
                    this.$store.dispatch("currentUser/fetch");
                })
                .finally(() => {
                    this.$emit(
                        "input",
                        this.motto_progress + this.image_progress
                    );
                    this.editMotto = false;
                });
        },

        openFileUpload() {
            this.$eventBus.emit("avatar-upload-requested");
        },

        openImageUploadFailedPopup() {
            this.$eventBus.emit("abstract-modal-requested", {
                component: ImageUploadFailedPopUp,
                props: {},
            });
        },

        deleteImage() {
            console.log("delete!");
            axios
                .delete("/api/profile/avatar")
                .then(() => {
                    this.$store.fetch();
                })
                .finally(() => {
                    this.changed += 1;
                    this.portrait.avatar = null;
                });
        },
    },

    beforeDestroy() {
        this.$eventBus.off("avatar-uploaded");
    },
};
</script>

<style lang="scss" scoped>
.root {
    min-height: 220px;

    @media (max-width: 991px) {
        min-height: 300px;
    }
}

.avatar {
    display: block;
    width: 6rem;
    height: 6rem;
    border-radius: 50%;
    margin-bottom: 0.625rem;
}

.profile--image {
    width: 100px;
    //height: 100px;
    background-size: contain !important;
    border-radius: 45%;
}

.profile--image .hidden {
    visibility: hidden;
}
.profile--image .icon--edit-bottom {
    bottom: 35px;
}

.cropper {
    height: 600px;
    background: #dddddd;
}

.textarea,
textarea.textarea:disabled {
    min-height: 110px;
    box-sizing: border-box;
    background: #ededed;
    color: #818181;
    -webkit-text-fill-color: #121212;
    -webkit-opacity: 1;
}

.textarea {
    &:not(:disabled) {
        &:focus {
            border: 2px solid #b5b5b5 !important;
            outline: none;
        }
    }
}

.deleteHandler {
    font-size: 11px;
    cursor: pointer;
}
</style>
