<template>
    <default-layout>
        <div class="row">
            <div class="col-sm-12 col-lg-6">
                <div class="rounded-box rounded-box--100h p-2 p-lg-4">
                    <h1 class="mb-2">Die TrostHelden-Mitgliedschaft</h1>

                    <p class="mini-headline mb-2">
                        Geh’ den nächsten Schritt in deiner Trauerarbeit.
                    </p>

                    <p class="mini-headline mb-1">
                        Deine Vorteile als TrostHelden-Mitglied:
                    </p>
                    <highlights class="mb-4" :highlights="premiumHighlights" />

                    <p class="mini-headline mb-4">
                        So einfach geht’s: Laufzeit auswählen, Daten angeben,
                        Trauerfreund finden!
                    </p>

                    <div class="container">
                        <div v-if="plans.length" class="row mt-4 mb-4">
                            <div v-for="plan in plans" class="col-12 col-sm-4 px-1 mb-2">
                                <payment-box
                                    :title="plan.title"
                                    :price="plan.price"
                                    :url="plan.url"
                                />
                            </div>
                        </div>
                      <div v-else class="d-flex justify-content-center align-items-center my-4">
                        <span

                            aria-hidden="true"
                            class="spinner-grow spinner-grow-sm mb-1"
                            role="status"
                        ></span>
                      </div>
                    </div>
                    <p class="disclaimer">
                        Deine Mitgliedschaft verlängert sich bequem automatisch. Du kannst
                        deine Mitgliedschaft bis 7 Tage vor Ablauf des Abos
                        unkompliziert über den Button "Mitgliedschaft kündigen"
                        in den
                        <router-link :to="{ name: 'settings' }"
                            >Einstellungen</router-link
                        >
                        kündigen. Nach Klick auf eines der Pakete wirst du zu
                        unserem Bestellformular weitergeleitet. Wir unterstützen
                        die folgenden Zahlungsmittel: PayPal, Lastschrift,
                        Kreditkarte.
                    </p>
                </div>
            </div>
            <div class="col-sm-12 col-lg-6">
                <div class="rounded-box rounded-box--100h p-2 p-lg-4">
                    <h2 class="mb-2">
                        Warum gibt es die TrostHelden-Mitgliedschaft?
                    </h2>
                    <p class="mb-4">
                        Wir von TrostHelden glauben an die heilende Wirkung des
                        Austauschs zwischen zwei Trauernden mit gleichem
                        Schicksalsschlag, wenn diese
                        <b>die gleiche Trauersprache sprechen</b>. Deswegen
                        haben wir TrostHelden gegründet.<br /><br />
                        Dafür möchten wir euch einen
                        <b>sicheren, geschützten und werbefreien Raum</b> für
                        eure Trauerarbeit bieten.
                    </p>

                    <h2 class="mb-2">
                        Daraus ergibt sich unser TrostHelden-Versprechen:
                    </h2>

                    <highlights :highlights="promiseHighlights" />
                </div>
            </div>
        </div>
    </default-layout>
</template>

<script>
import Highlights from "../components/custom_elements/highlights";
import PaymentBox from "../components/premium/payment-box";
import { mapGetters } from "vuex";
import { price as formatPrice } from "../utils/checkout";

export default {
    name: "Premium",
    components: { Highlights, PaymentBox },
    mounted() {
      this.setPremiumPaywallSeen();
      this.setPlans();
    },
    data() {
        return {
            plans: [],
            premiumHighlights: [
                {
                    icon: "check",
                    text:
                        "Nimm Kontakt zu anderen Mitgliedern auf und finde deinen Trauerfreund",
                },
                {
                    icon: "check",
                    text:
                        "Unbegrenzt und sicher mit anderen Mitglieder kommunizieren",
                },
                {
                    icon: "check",
                    text: "Wähle die Laufzeit aus, die am besten zu dir passt.",
                },
                {
                    icon: "star",
                    text:
                        "Finde Heilung auf diesem neuen Weg deiner Trauerarbeit",
                },
            ],
            promiseHighlights: [
                { icon: "star", text: "Ein sicherer Raum für deine Trauer" },
                {
                    icon: "star",
                    text:
                        "Aus der Praxis. Ein von Experten entwickelter Fragebogen dient als Basis für Kontaktvorschlägen zu anderen Trauernden mit ähnlichem Verlust.",
                },
                {
                    icon: "star",
                    text:
                        "Keine Werbung. Wir möchten, dass ihr euch auf eure Trauerarbeit konzentieren könnt.",
                },
                {
                    icon: "star",
                    text:
                        "Eure Daten sind sicher. Wir geben keine Daten an Werbenetzwerke oder andere Dienste weiter.",
                },
                {
                    icon: "star",
                    text: "Unsere Daten liegen auf deutschen Servern.",
                },
            ],
        };
    },
    methods: {
        async setPlans() {
          try {
            let {data} = await axios.post(`/api/payments/plans/standards`);

            const plans = data.plans.map((plan) => {
              const price = plan.price /plan.period.Quantity;
              const title = `${plan.period.Quantity} ${(plan.period.Quantity > 1)? 'Monate': 'Monat'}`;
              return {
                ...plan,
                title,
                price: formatPrice(price),
                url: `/checkout/${plan.code}`
              };
            });

            this.plans = [...plans];
          } catch (e) {
            console.error(e)
          }
        },
        setPremiumPaywallSeen() {
            axios
                .post(`/api/user/set-premium-paywall-seen`)
                .then(async ({ data }) => {})
                .catch((err) => {
                    console.error(err);
                });
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../sass/setup/variables";
.row {
    color: $brand-color-primary;
    h1,
    h2 {
        font-size: 20px;
    }
    h2 {
        font-style: italic;
        font-weight: normal;
    }
    .disclaimer {
        font-size: 0.8rem;
    }
    .rounded-box--100h {
        height: 100%;
    }
}
</style>
