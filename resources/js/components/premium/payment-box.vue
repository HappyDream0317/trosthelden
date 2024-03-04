<template>
    <div class="payment-box rounded-box">
        <div class="payment-box__title pt-1 pb-1">
            {{ title }}
        </div>
        <div class="payment-box__body p-1">
            <a :href="paymentUrl" class="btn btn-primary"
                >{{ price }} pro Monat</a
            >
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "payment-box",
    props: ["title", "price", "url", "isCoupon"],
    computed: {
        tagParam() {
            if (this.isCoupon) {
                /**
                 * We cannot use the same tag because at the end
                 * the tag will be used for the externalCustomerId
                 * which has to be unique on billwerks side.
                 */
                return `?tag=coupon-${new Date().getTime()}`;
            }
            return this.currentUserId ? `?tag=${this.currentUserId}` : "";
        },
        paymentUrl() {
            return `${this.url}${this.tagParam}`;
        },
        ...mapGetters("currentUser", {
            currentUserId: "getId",
        }),
    },
};
</script>

<style scoped lang="scss">
@import "../../../sass/setup/variables";
.payment-box {
    border: 1px solid $brand-color-primary;
    &__title {
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        border-bottom: 1px solid $brand-color-primary;
        color: $brand-color-primary;
        text-align: center;
        font-weight: bold;
    }
    &__body {
        .btn {
            width: 100%;
            @media screen and (min-width: 1024px) {
                font-size: 0.9rem;
            }
        }
    }
}
</style>
