<template>
  <default-layout>
    <div class="row">
      <div class="col-lg-12 mt-4 text-center">
        <p v-if="!consent.marketing" class="mb-3">
            <b>
                Um Ihnen ein besseres Erlebnis zu bieten, sollten Sie die <button class="btn btn-link p-0" @click="toggleConsent">Marketin-Option in Ihren Cookie-Einstellungen</button> aktivieren.
            </b>
        </p>
        <p>
            <small>
                Wenn diese Seite Sie nicht innerhalb weniger Sekunden an Ihr Ziel weiterleitet, <button class="btn btn-link p-0" @click="redirect">klicken Sie hier</button>
            </small>
        </p>
      </div>
    </div>
  </default-layout>
</template>

<script>
import {mapGetters} from "vuex";
import {setCookie} from "../../utils/cookie";

export default {
      name: "B2BPartner",
      props: ["slug"],
            data(){
                  return {
                      code: null,
                      target: null
                  }
            },
      computed: {
        ...mapGetters("currentUser", {
          isLoggedIn: "isLoggedIn",
          isBusinessAccount: "isBusinessAccount"
        }),
        consent(){
          return window.Cookiebot.consent;
        }
      },
      async mounted() {
        window.addEventListener('CookiebotOnConsentReady',  () => this.setConfig());
        await this.init();
      },
      methods: {
          async init() {
             try {

                   const response = await axios.get(`/api/b2b/partner/redirect/${this.slug}`);

                   if(!response?.data) return Promise.reject(response);

                   const {code, target} = response.data;

                   if(!code || !target) return Promise.reject(response);

                   this.code = code;
                   this.target = target;

                   this.setConfig();

             } catch (error) {
                 if(this.isLoggedIn)
                    this.$router.push({path: this.isBusinessAccount ? '/b2b/dashboard' : '/dashboard'});
                 else
                    this.$router.push({name: "login"});
             }
         },
          setConfig() {

              if(!this.consent.marketing) return;

              setCookie("partner", this.code);

              this.redirect();
          },
          redirect() {
              window.location.href = this.target;
          },
          toggleConsent() {
              window.Cookiebot.show();
          }
      }
}
</script>

<style scoped>

</style>