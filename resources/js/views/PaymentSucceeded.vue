<template>
    <default-layout>
        <div class="row">
            <div class="col-lg-12">
                <h1>Vielen Dank für deine Bestellung!</h1>
                <br />
                <p v-if="isPremiumOrder">
                    Du wirst in
                    {{ countdown }}
                    Sekunden weitergeleitet.
                </p>
                <p v-if="isPremiumOrder">
                    Du kannst Trauerfreund-Anfragen jetzt an andere Mitglieder
                    versenden und unbegrenzt mit diesen Nachrichten
                    schreiben.<br />
                    Wir empfehlen dir, am besten gleich ein paar Anfragen zu
                    versenden und wünschen dir einen heilsamen Austausch mit
                    deinen Trauerfreunden.<br /><br />
                    Im Hintergrund wird gerade der Zahlungseingang überprüft.
                    Dies dauert im Normalfall nicht länger als 10 Sekunden.<br />
                </p>
                <p v-if="isCouponOrder">
                    Der Gutschein als PDF wurde an deine E-Mail Adresse
                    gesendet.
                    <br />
                </p>
            </div>
        </div>
    </default-layout>
</template>

<script>
import { trackMatomoEvent } from "../matomo";

export default {
    name: "PaymentSucceeded",
    data() {
        return {
            isPremiumOrder: false,
            isCouponOrder: false,
            countdown: 10,
        };
    },
    async mounted() {
        const { contractId } = this.$route.query;
        if (!contractId) return;

        const response = await axios
            .get(`/api/payments/customer/status/${contractId}`)
            .then(async ({ data }) => data)
            .catch((err) => console.error(err));

        console.log(response);

        if (response && response.orderType === "premium") {
            this.handlePremiumOder(response);
        }
        if (response && response.orderType === "coupon") {
            this.handleCouponOder();
        }
    },
    methods: {
        async handlePremiumOder(response) {
            this.isPremiumOrder = true;
            trackMatomoEvent("payment", "premium start");
            await this.sendMail("premium");

            const interval = setInterval(async () => {
                this.countdown -= 1;
                if (this.countdown === 0) {
                    clearInterval(interval);
                    if (response.success) {
                        await this.$store.dispatch("currentUser/fetch");
                        this.$router.push({ name: "dashboard" });
                    } else {
                        this.$router.push({ name: "paymenterror" });
                    }
                }
            }, 1000);
        },
        async handleCouponOder() {
            this.isCouponOrder = true;
            trackMatomoEvent("payment", "coupon sold");
            // await this.sendMail('coupon')
            setTimeout(
                () => (location.href = "https://www.trosthelden.de"),
                5000
            );
            // TODO: Payment Error Page Handling, Kein Counter
        },
        sendMail(mailType) {
            return axios
                .post(`/api/payments/send-mail`, {
                    mailType,
                })
                .then(async ({ data }) => {
                    console.log(data);
                })
                .catch((err) => console.error(err));
        },
    },
};
</script>

<style scoped></style>
