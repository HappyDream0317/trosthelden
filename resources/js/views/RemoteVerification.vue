<template>
  <div
      id="verification"
      class="d-flex justify-content-center align-content-center"
  >
    <spinner
        v-if="spinnerActive"
        color="color--primary"
        message="Wir bearbeiten Ihre Anmeldung, bitte haben Sie einen Moment Geduld."
    />
    <div
        class="verified d-flex flex-column justify-content-center align-items-center color--primary"
        v-else-if="success"
    >
      <p>
        Deine Anmeldung wurde erfolgreich bearbeitet. Du wirst in {{ redirectCounter }} Sekunden weitergeleitet.
      </p>
    </div>
  </div>
</template>

<script>
import Spinner from "../components/groups/Spinner";
import {getPartnerCodeFormURL} from "../utils/checkout";
import {mapGetters} from "vuex";
import Repository from "../repositories/RepositoryFactory";

const PaymentRepository = Repository.get("payment");

export default {
  name: "RemoteVerification",
  components: {
    Spinner,
  },
  props: ["user_id", "hash", "variant_id"],
  data() {
    return {
      spinnerActive: true,
      success: false,
      redirectCounter: 5,
    };
  },
  watch: {
    success: function (value, oldValue) {
      if (oldValue === false && value === true) {
        setInterval(() => {
          this.redirectCounter = this.redirectCounter - 1;
        }, 1000);
      } else {
        console.log("failed?");
      }
    },
    redirectCounter: function (value) {
      if (value === 1) {
        this.redirect();
        this.$emit("status", true);
      }
    },
  },
  computed: {
    ...mapGetters("currentUser", {
      isLoggedIn: "isLoggedIn",
    }),
  },
  async mounted() {
    await this.login()
        .then(() => {
          this.success = true;
          this.spinnerActive = false;
        })
        .catch((err) => {
          console.error(err);
        });
  },
  methods: {
    login() {
      return this.$store
          .dispatch("currentUser/loginRemote", {
            id: parseInt(this.user_id),
            token: this.hash
          });
    },
    async redirect() {
      let partner = await getPartnerCodeFormURL();
      let affliate = partner ? {partner} : {};

      if (this.variant_id) {
        let path = await this.getCheckoutURL();

        this.$router.push({path, query: {tag: this.user_id, ...affliate}});

      } else {
        this.$router.push({name: "guide", query: {...affliate}});
      }
    },
    async getCheckoutURL() {
      try {
        let {result} = await PaymentRepository.isB2BProduct(this.variant_id);
        return `${(result ? '/b2b' : '')}/checkout/${this.variant_id}`;
      } catch (e) {
        console.error(e);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
#verification {
  width: 100%;
  height: 100%;
  position: absolute;
  font-size: 24px;
}

.verified {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  p {
    line-height: 1.5;
    @media only screen and (max-width: 1023px) {
      text-align: center;
    }
  }
}
</style>
