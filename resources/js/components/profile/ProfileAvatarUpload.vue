<template>
    <transition name="modal" v-if="isActive">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <fa-icon
                        class="icon"
                        icon="times"
                        @click="onClose()"
                    ></fa-icon>
                    <div>
                        <slot name="body">
                            <h3>Profil-Bild hochladen</h3>

                            <div v-if="!image && !errored" class="select-image">
                                <p v-if="!errored && !isLoading">
                                    Wähle bitte kein Gruppenbild oder ein Foto,
                                    das in irgendeiner Form anzüglich wirken
                                    könnte. Wir möchten dich auch bitten, kein
                                    Foto von dem Verstorbenen hochzuladen. Warum
                                    ist ein Foto von dir wichtig? Anhand des
                                    Profilfotos können sich andere User im
                                    wahrsten Sinne des Wortes ein Bild von dir
                                    machen. Das ist für die Trauerfreundsuche
                                    von großer Bedeutung. Datenschutzhinweis:
                                    Bestimmte Elemente wie zum Beispiel auch
                                    dein Foto sind für alle einsehbar. Mehr
                                    Infos findest du bei den AGB´s und
                                    Datenschutz-Maßnahmen.
                                </p>
                            </div>

                            <div v-if="image && !errored" class="select-image">
                                <div v-if="isLoading" class="loading-spinner">
                                    <spinner />
                                </div>
                                <cropper
                                    ref="cropper"
                                    class="cropper"
                                    :class="{ hidden: isUpLoading }"
                                    :src="image"
                                    @ready="onItsReady"
                                    :stencil-props="{
                                        aspectRatio: 1,
                                    }"
                                    @change="change"
                                ></cropper>
                            </div>

                            <div class="error" v-if="errored">
                                Es trat ein Fehler auf.
                                <div v-for="errorMsg of errors">
                                    {{ errorMsg }}
                                </div>
                            </div>

                            <div v-if="image" class="action">
                                <button
                                    v-if="!isLoading"
                                    @click="onUpload"
                                    type="button"
                                    class="btn btn-default btn-primary mt-2 float-right"
                                >
                                    <span>Bild hochladen</span>
                                </button>
                            </div>
                            <div v-else class="action">
                                <button
                                    @click="$refs.file.click()"
                                    type="button"
                                    class="btn btn-default btn-primary float-right"
                                >
                                    Bild auswählen
                                </button>
                                <input
                                    type="file"
                                    ref="file"
                                    @change="onChangeInput"
                                    style="display: none"
                                />
                            </div>
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import { Cropper } from "vue-advanced-cropper";
import 'vue-advanced-cropper/dist/style.css';

export default {
    name: "ProfileAvatarUpload",
    props: [],
    components: { Cropper },
    data: function () {
        return {
            file: null,
            isLoading: false,
            isUpLoading: false,
            isActive: false,
            base64: "",
            image: "",
            errored: false,
            errors: [],
        };
    },
    methods: {
        onItsReady() {
            this.isLoading = false;
        },
        onClose() {
            this.isActive = false;
            this.base64 = "";
            this.file = null;
            this.image = "";
        },
        onChangeInput(event) {
            this.isLoading = true;
            const file = event.target.files[0];
            const size = event.target.files[0].size;
            this.file = event.target.files[0];

            this.errors = [];
            this.errored = false;

            if (size > 18000000) {
                // 18mb
                this.errored = true;
                this.errors.push(
                    "Bitte wähle eine maximale Dateigrößem von 18mb aus"
                );
                return;
            }

            if (
                file.type !== "image/png" &&
                file.type !== "image/jpg" &&
                file.type !== "image/jpeg" &&
                file.type !== "image/gif"
            ) {
                this.errored = true;
                this.errors.push(
                    "Bitte wähle eine Bilddatei aus (jpg, jpeg, png, gif)."
                );
                return;
            }

            const reader = new FileReader();

            reader.readAsDataURL(file);
            reader.onload = () => {
                this.image = reader.result;
            };
        },
        change({ canvas }) {
            this.base64 = canvas.toDataURL();
        },
        onUpload() {
            this.isLoading = true;
            this.isUpLoading = true;
            const { canvas } = this.$refs.cropper.getResult();
            if (canvas) {
                const form = new FormData();
                canvas.toBlob((blob) => {
                    if (blob.size > 6000000) {
                        // 6mb
                        this.errored = true;
                        const msg = `Der Bildausschnitt ist zu groß. Wähle ein anderes Bild oder verkleinere ihn.`;
                        if (!this.errors.includes(msg)) this.errors.push(msg);
                        this.isUpLoading = false;
                        return;
                    }

                    form.append("file", blob);
                    axios
                        .post("/api/profile/avatar", form, {
                            header: {
                                "Content-Type": "multipart/form-data",
                            },
                        })
                        .then((response) => {
                            if (response.data.success) {
                                this.$eventBus.emit(
                                    "avatar-uploaded",
                                    response.data
                                );
                                this.$store.dispatch("currentUser/fetch");
                            } else {
                                this.errored = true;
                            }
                        })
                        .catch(() => {
                            this.errored = true;
                        })
                        .finally(() => {
                            setTimeout(() => {
                                this.isLoading = false;
                                this.isUpLoading = false;
                                this.isActive = false;
                                this.base64 = "";
                                this.image = "";
                            }, 1000);
                        });
                }, "image/jpeg");
            }
        },
    },
    mounted: function () {
        this.image = "";
        this.errored = false;
        this.errors = [];
        this.$eventBus.on("avatar-upload-requested", () => {
            this.isActive = true;
        });
    },
    beforeDestroy() {
        this.$eventBus.off("avatar-upload-requested");
    },
};
</script>

<style lang="scss" scoped>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    position: relative;
    width: 90%;
    margin: 0px auto;
    padding: 10px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    overflow: hidden;
    @media screen and (min-width: 460px) {
        width: 460px;
        padding: 20px 30px;
    }
    /*@media screen and (min-width: 768px) {*/
    /*    width: 660px;*/
    /*}*/
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.select-image {
    margin-top: 1rem;
    position: relative;
    min-height: 260px;
    .loading-spinner {
        position: absolute;
        z-index: 9999;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
}

.modal-default-button {
    float: right;
}

/*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
     */

.modal-enter {
    opacity: 0;
}

.modal-leave-active {
    opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}

.fa-times {
    position: absolute;
    right: 10px;
    top: 10px;
    cursor: pointer;
    font-size: 1.3rem;
}

.cropper {
    /*visibility: hidden;*/

    height: 280px;
    width: 280px;
    margin: 0 auto;
    @media screen and (min-width: 375px) {
        height: 320px;
        width: 320px;
    }
    @media screen and (min-width: 768px) {
        height: 400px;
        width: 400px;
    }
    /*&--cropper-shop{*/
    /*    visibility: visible;*/
    /*}*/
}

.loading-placeholder {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 150px;
}

.uploading {
    margin-top: 30px;
    .d-flex {
        align-items: flex-end !important;
    }
}

.error {
    text-align: center;
    font-weight: bold;
    margin: 1rem 0;
    color: #ad2222;
}

.hidden {
    display: none;
}
</style>
