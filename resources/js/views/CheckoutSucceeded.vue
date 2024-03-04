<template>
  <default-layout>
    <div v-if="isLoading" class="d-flex justify-content-center align-items-center my-4">
      <span
          aria-hidden="true"
          class="spinner-grow spinner-grow-sm mb-1"
          role="status"
      ></span>
    </div>
    <div v-else class="row">
      <div class="col-lg-12 mt-4 text-center">
        <p v-if="isB2BOrder">
          Gerne Bestätigen wir Ihnen Ihre Bestellung der TrostHelden-Gutscheine.<br/>
          Die Codes sowie weitere Informationen erhalten Sie separat per Mail.
        </p>
        <p v-if="isPremiumOrder">
          Deine Bestellung war erfolgreich. Du wirst in {{ countdown }} Sekunden zur Startseite weitergeleitet.
        </p>
        <p v-if="isCouponOrder">
          Gerne Bestätigen wir dir deine Bestellung eines TrostHelden-Gutscheins.<br>
          Den Gutschein-Code erhältst Du per Mail.
        </p>
      </div>
      <div v-if="!isPremiumOrder" class="col-lg-12 mt-4 d-flex justify-content-center align-items-center">
        <button
            @click="redirect"
            class="btn btn-primary btn-save"
        >
          <fa-icon icon="check"></fa-icon>
          Ok
        </button>
      </div>
    </div>
  </default-layout>
</template>

<script>
import {mapGetters} from "vuex";
import Repository from "../repositories/RepositoryFactory";

const PaymentRepository = Repository.get("payment");

export default {
  name: "CheckoutSucceeded",
  props: ["variant_id"],
  data() {
    return {
      isB2BOrder: false,
      isPremiumOrder: false,
      isCouponOrder: false,
      countdown: 10,
    };
  },
  computed: {
    ...mapGetters("currentUser", {
      hasCompletedFrabo: "hasCompletedFrabo",
      isVerified: "isVerified",
      isLoggedIn: "isLoggedIn",
    }),
    ...mapGetters("env", {
      getValueFromEnv: "getValueFromEnv"
    }),
    isLoading() {
      return (!this.isB2BOrder && !this.isPremiumOrder && !this.isCouponOrder);
    }
  },
  async mounted() {
    try {
      if (!this.variant_id) return false;

      const isStandard = await PaymentRepository.isStandardProduct(this.variant_id);
      if (isStandard.result) {
        this.isPremiumOrder = true;
        this.handleCounter();
        return false;
      }

      const isB2B = await PaymentRepository.isB2BProduct(this.variant_id);
      if (isB2B.result) {
        this.isB2BOrder = true;
        return false;

      }
      const isCoupon = await PaymentRepository.isCouponProduct(this.variant_id);
      if (isCoupon.result) {
        this.isCouponOrder = true;
      }

    } catch (e) {
      console.error(e)
    }
  },
  methods: {
    handleCounter() {
      const interval = setInterval(() => {
        this.countdown -= 1;
        if (this.countdown === 1) {
          clearInterval(interval);
          this.redirect();
        }
      }, 1000);
    },
    redirect() {
      if (this.isLoggedIn && !this.isB2BOrder) {
        this.$router.push({name: !this.isVerified && !this.hasCompletedFrabo ? "guide" : "dashboard"});
      } else {
        window.location.href = this.getValueFromEnv("TROSTHELDEN_URL").value;
      }
    },
  },
};
</script>

<style scoped></style>
