<template>
    <default-layout>
        <div class="row">
            <MainContent :has-sidebar="true">
                <h2>Benachrichtigung</h2>
                <div class="notifications rounded-box p-4">
                    <p>
                        Es gibt verschiedene Bereiche, aus denen du
                        Benachrichtigungen per E-Mail von uns bekommen kannst.
                        Wir schicken dir nur diejenigen Nachrichten, die du auch
                        wirklich erhalten möchtest.
                    </p>

                    <div class="group d-flex flex-column rounded-box p-4 mb-4">
                        <h4>Nachrichten</h4>
                        <div
                            class="option d-flex justify-content-between align-items-center"
                        >
                            <p>
                                Möchtest du eine Info, wenn du eine persönliche
                                Nachricht erhalten hast?
                            </p>
                            <switches
                                @change="saveNotifications()"
                                v-model="notifications.chat_message"
                            />
                        </div>
                    </div>

                    <div class="group d-flex flex-column rounded-box p-4 mb-4">
                        <h4>Trauerfreund-Anfragen</h4>
                        <div
                            class="option d-flex justify-content-between align-items-center"
                        >
                            <p>
                                Du möchtest eine E-Mail-Benachrichtigung, wenn
                                eine Trauerfreund-Anfrage an dich gerichtet ist?
                            </p>
                            <switches
                                @change="saveNotifications()"
                                v-model="notifications.friend_request"
                            />
                        </div>
                        <div
                            class="option d-flex justify-content-between align-items-center"
                        >
                            <p>
                                Möchtest du informiert werden, wenn deine
                                Trauerfreund-Anfrage angenommen wurde?
                            </p>
                            <switches
                                @change="saveNotifications()"
                                v-model="
                                    notifications.friend_request_confirmation
                                "
                            />
                        </div>
                    </div>

                    <!--                    <div class="group d-flex flex-column rounded-box p-4 mb-4">-->
                    <!--                        <h4>-->
                    <!--                            Gruppen (Benachrichtigungen aktuell noch nicht-->
                    <!--                            verfügbar)-->
                    <!--                        </h4>-->
                    <!--                        <div-->
                    <!--                            class="option d-flex justify-content-between align-items-center"-->
                    <!--                        >-->
                    <!--                            <p>-->
                    <!--                                Möchtest du per E-Mail erfahren, wenn ein-->
                    <!--                                anderes Mitglied auf deinen Beitrag in einer-->
                    <!--                                Trauergruppe geantwortet hat?-->
                    <!--                            </p>-->
                    <!--                            <switches-->
                    <!--                                @change="saveNotifications()"-->
                    <!--                                v-model="notifications.my_comment_replied"-->
                    <!--                            />-->
                    <!--                        </div>-->
                    <!--                        <div-->
                    <!--                            class="option d-flex justify-content-between align-items-center"-->
                    <!--                        >-->
                    <!--                            <p>-->
                    <!--                                Möchtest du über neue Beiträge in deiner-->
                    <!--                                Trauergruppe informiert werden?-->
                    <!--                            </p>-->
                    <!--                            <switches-->
                    <!--                                @change="saveNotifications()"-->
                    <!--                                v-model="notifications.my_post_commented"-->
                    <!--                            />-->
                    <!--                        </div>-->
                    <!--                        <div-->
                    <!--                            class="option d-flex justify-content-between align-items-center"-->
                    <!--                        >-->
                    <!--                            <p>-->
                    <!--                                Möchtest du informiert werden, wenn einer deiner-->
                    <!--                                Trauerfreund einen neuen Beitrag in einer-->
                    <!--                                Trauergruppe geschrieben hat?-->
                    <!--                            </p>-->
                    <!--                            <switches-->
                    <!--                                @change="saveNotifications()"-->
                    <!--                                v-model="notifications.friend_post"-->
                    <!--                            />-->
                    <!--                        </div>-->
                    <!--                    </div>-->

                    <!--                    <div class="group d-flex flex-column rounded-box p-4 mb-4">-->
                    <!--                        <h4>-->
                    <!--                            Matches (Benachrichtigungen aktuell noch nicht-->
                    <!--                            verfügbar)-->
                    <!--                        </h4>-->
                    <!--                        <div-->
                    <!--                            class="option d-flex justify-content-between align-items-center"-->
                    <!--                        >-->
                    <!--                            <p>-->
                    <!--                                Wenn du mal eine Zeit lang nicht eingeloggt-->
                    <!--                                warst, möchtest du dann über neue und passende-->
                    <!--                                Trauerfreund-Vorschläge per E-Mail informiert-->
                    <!--                                werden? Diese Mail senden wir frühestens 3 Tage-->
                    <!--                                nach dem letzten Login.-->
                    <!--                            </p>-->
                    <!--                            <switches-->
                    <!--                                @change="saveNotifications()"-->
                    <!--                                v-model="notifications.matches"-->
                    <!--                            />-->
                    <!--                        </div>-->
                    <!--                    </div>-->

                    <div class="group d-flex flex-column rounded-box p-4 mb-4">
                        <h4>Generelle Benachrichtigungen</h4>
                        <div
                            class="option d-flex justify-content-between align-items-center"
                        >
                            <p>
                                Bei TrostHelden wird es immer wieder
                                interessante Aktionen (z.B. Rabattaktionen)
                                geben. Möchtest du darüber per E-Mail informiert
                                werden?
                            </p>
                            <switches
                                @change="saveNotifications()"
                                v-model="notifications.promotions"
                            />
                        </div>
                        <div
                            class="option d-flex justify-content-between align-items-center"
                        >
                            <p>
                                Die ständige Verbesserung unserer Trostangebote
                                liegt uns sehr am Herzen. Das gelingt am besten
                                in Zusammenarbeit mit unseren Mitgliedern.<br />
                                Dürfen wir dich im Rahmen von
                                Mitgliederbefragungen per E-Mail kontaktieren?
                            </p>
                            <switches
                                @change="saveNotifications()"
                                v-model="notifications.surveys"
                            />
                        </div>
                        <div
                            class="option d-flex justify-content-between align-items-center"
                        >
                            <p>
                                Wir möchten dich auf deinem Weg zu einem
                                Trauerfreund begleiten. Daher senden wir dir
                                gelegentlich E-Mails, die dich unterstützen
                                sollen, den nächsten Schritt in deiner
                                Trauerarbeit zu gehen.<br />
                                Sollen wir dich auf diesem Weg weiterhin an die
                                Hand nehmen?
                            </p>
                            <switches
                                @change="saveNotifications()"
                                v-model="notifications.automation"
                            />
                        </div>
                        <div
                            class="option d-flex justify-content-between align-items-center"
                        >
                            <p>
                                Du bekommst von uns systemrelevante Mails.
                                Hierzu zählen z.B. Informationen über neue
                                Funktionen und andere Erweiterungen, Änderungen
                                in den AGB, Sonderaktionen etc.
                            </p>
                            <switches
                                @change="saveNotifications()"
                                v-model="notifications.updates"
                                :disabled="true"
                            />
                        </div>
                    </div>
                </div>
            </MainContent>
        </div>
    </default-layout>
</template>

<script>
import DefaultLayout from "../layouts/DefaultLayout";
import MainContent from "../layouts/MainContent";
import Switches from "../components/custom_elements/switches";

export default {
    name: "Notifications",
    components: { DefaultLayout, MainContent, Switches },
    data() {
        return {
            notifications: {
                chat_message: true,
                friend_request: true,
                friend_request_confirmation: true,
                my_comment_replied: true,
                my_post_commented: true,
                friend_post: true,
                matches: true,
                promotions: true,
                surveys: true,
                automation: true,
                updates: true,
            },
            loading: false,
        };
    },
    mounted() {
        this.getNotifications();
    },
    methods: {
        getNotifications() {
            if (this.loading) return;

            this.loading = true;
            axios
                .get("/api/user/notifications")
                .then((response) => {
                    if (response.data.notifications !== null)
                        this.notifications = response.data.notifications;
                    this.loading = false;
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        saveNotifications() {
            if (this.loading) return;

            this.loading = true;
            axios
                .post("/api/user/notifications", {
                    notifications: this.notifications,
                })
                .then((response) => {
                    this.user = response.data;
                    this.loading = false;
                })
                .catch((err) => {
                    console.error(err);
                });
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../sass/setup/variables";
.notifications {
    .group {
        background: $light-grey;

        &:last-child {
            margin-bottom: 0 !important;
        }

        .option {
            &:last-child {
                p {
                    margin-bottom: 0;
                }
            }

            p {
                width: calc(100% - 40px);
                padding-right: 50px;
            }
            .vue-switcher {
                width: 40px;
            }
        }
    }

    @media (max-width: 600px) {
        .group {
            .option {
                p {
                    padding-right: 30px;
                }
            }
        }
    }

    @media (max-width: 450px) {
        padding: 1rem !important;
        .group {
            padding: 1rem !important;
            .option {
                flex-direction: column;

                &:last-child {
                    p {
                        margin-bottom: 1rem;
                    }
                    .vue-switcher {
                        margin-bottom: 0;
                    }
                }

                p {
                    width: 100%;
                    padding-right: 0;
                }
                .vue-switcher {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                    margin-bottom: 1rem;
                }
            }
        }
    }
}
</style>
