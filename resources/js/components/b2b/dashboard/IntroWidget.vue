<template>
  <div
      v-if="!hasSeenElement(elementName)"
      class="dashboard-intro-widget position-relative rounded-box mb-5 p-4"
  >
    <fa-icon class="fa-times-circle position-absolute" icon="times-circle" @click="onHide"/>
    <h3>Willkommen auf deiner Startseite</h3>
    <div class="mt-3">
      <div v-if="loading" class="d-flex justify-content-center">
        <div class="spinner-border" role="status">
          <span class="visually-hidden-focusable">Loading...</span>
        </div>
      </div>
      <template v-else-if="!loading && hasFlatrate">
        <p>
          Auf Ihrem Dashboard haben Sie alles auf einen Blick. Über den Button „Code generieren“ kommen Sie zu neuen
          TrostHelden-Gutscheinen. Dieser erscheint in der Liste der offenen Gutscheine. Sie tragen den Namen Ihres
          Kunden
          ein, downloaden den entsprechenden Gutschein und markieren das "Ja". Ihr Geschenk an Ihren Kunden ist fertig
          für
          die Übergabe!
        </p>
        <p>
          Im unteren Teil der Liste erhalten Sie einen Überblick über die vergebenen TrostHelden-Gutscheine. Zudem sehen
          Sie, ob Ihr Kunde Ihr Geschenk bereits eingelöst hat. Dieser Zeitpunkt fällt, je nach Handlungsfähigkeit in
          der
          Trauer, von Mensch zu Mensch sehr individuell aus. Ob eingelöst oder nicht: Sie haben einen guten Grund, nach
          einigen Wochen erneut Kontakt zu Ihrem Kunden aufzunehmen. Diese Fürsorge führt zu überzeugten
          Weiterempfehlungen!
        </p>
      </template>
      <template v-else-if="!loading && !hasFlatrate">
        <template v-if="isAllowedTo('view funeral-company_intro')">
          <p>
            Auf Ihrem Dashboard sehen Sie auf einen Blick, wie viele erworbene TrostHelden-Gutscheine Sie noch zur
            Verfügung haben. Die Handhabung der Vergabe ist kinderleicht: Sie tragen den Namen Ihres Kunden ein,
            downloaden den entsprechenden Gutschein und markieren das "Ja". Ihr Geschenk an Ihren Kunden ist fertig für
            die Übergabe!
          </p>
          <p>
            Im unteren Teil der Liste erhalten Sie einen Überblick über die vergebenen TrostHelden-Gutscheine. Zudem
            sehen Sie, ob Ihr Kunde Ihr Geschenk bereits eingelöst hat. Dieser Zeitpunkt fällt, je nach
            Handlungsfähigkeit
            in der Trauer, von Mensch zu Mensch sehr individuell aus. Ob eingelöst oder nicht: Sie haben einen guten
            Grund, nach einigen Wochen erneut Kontakt zu Ihrem Kunden aufzunehmen. Diese Fürsorge führt zu überzeugten
            Weiterempfehlungen!
          </p>
        </template>
        <template v-else>
          <p>
            Auf Ihrem Dashboard sehen Sie auf einen Blick, wie viele erworbene TrostHelden-Gutscheine Sie noch zur
            Verfügung haben. Die Handhabung der Vergabe ist kinderleicht: Sie tragen den Namen Ihres Mitarbeiters /
            Ihrer Mitarbeiterin ein, downloaden den entsprechenden Gutschein und markieren das "Ja". Ihr Geschenk an
            Ihre Mitarbeiter ist fertig für die Übergabe!
          </p>
          <p>
            Im unteren Teil der Liste erhalten Sie einen Überblick über die vergebenen TrostHelden-Gutscheine. Zudem
            sehen Sie, ob IhrE MitarbeiterIn Ihr Geschenk bereits eingelöst hat. Dieser Zeitpunkt fällt, je nach
            Handlungsfähigkeit in der Trauer, von Mensch zu Mensch sehr individuell aus.
          </p>
        </template>
      </template>
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: "B2BDashboardIntroWidget",
  data() {
    return {
      elementName: "b2b-dashboard-intro",
    };
  },
  computed: {
    ...mapGetters("currentUser", {
      hasSeenElement: "hasSeenElement",
      isAllowedTo: "isAllowedTo"
    }),
    ...mapGetters("b2bUser", {
      hasFlatrate: "getHasFlatrate"
    }),
    loading(){
      return this.hasFlatrate === null;
    }
  },
  methods: {
    onHide() {
      axios
          .post("/api/user/has-seen", {
            element: this.elementName,
          })
          .then(() => {
            this.$store.dispatch(
                "currentUser/addLastSeenElement",
                this.elementName
            );
          })
          .catch((err) => {
            console.error(err);
          });
    },
  }
}
</script>

<style lang="scss" scoped>
.dashboard-intro-widget {
  .fa-times-circle {
    cursor: pointer;
    right: 0.75rem;
    top: 0.6rem;
  }
}
</style>