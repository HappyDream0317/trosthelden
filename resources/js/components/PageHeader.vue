<template>
  <header class="header">
    <nav class="navbar navbar-expand-lg navbar-light main-navigation">
      <div class="align-items-center d-flex justify-content-between w-100">
        <router-link v-if="isLoggedIn" :to="{ name: 'dashboard' }" class="navbar-brand trosthelden-logo" exact>
          <img alt="Trosthelden Logo" class="img-fluid brand-logo" src="/img/trosthelden-logo-white.png"/>
        </router-link>
        <router-link v-if="!isLoggedIn" :to="{ name: 'login' }" class="navbar-brand trosthelden-logo" exact>
          <img alt="Trosthelden Logo" class="img-fluid brand-logo" src="/img/trosthelden-logo-white.png"/>
        </router-link>

        <div class="nav-wrapper">
          <button class="navbar-toggler border-0" data-bs-target="#navbarMenu" data-bs-toggle="collapse" type="button">
            <img alt="Menu" class="img-fluid icon-menu" src="/icons/layout/burger_menu.svg"/>
          </button>
          <div v-if="unreadCount" class="counter">
            {{ unreadCount }}
          </div>
        </div>
      </div>
      <div id="navbarMenu" class="collapse navbar-collapse">
        <div class="d-flex flex-column navbar-nav__container">
          <ul v-if="isLoggedIn" class="navbar-nav ms-lg-auto ms-xl-auto">
            <template v-if="isBusinessAccount">
              <li class="nav-item">
                <router-link :to="{ name: 'b2b_dashboard' }" class="nav-link d-flex align-items-center justify-content-center" exact>
                  <fa-icon class="fa-lg fa-fw" icon="home"></fa-icon>
                  <span class="ms-2">Startseite</span></router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'b2b_settings' }" class="nav-link d-flex align-items-center justify-content-center" exact>
                  <fa-icon class="fa-lg fa-fw" icon="cogs"></fa-icon>
                  <span class="ms-2">Einstellungen</span></router-link>
              </li>
            </template>
            <template v-else>
              <li class="nav-item">
                <router-link :to="{ name: 'dashboard' }" class="nav-link d-flex align-items-center justify-content-center" exact>
                  <fa-icon class="fa-lg fa-fw" icon="home"></fa-icon>
                  <span class="ms-2">Startseite</span></router-link>
              </li>
              <li v-if="isAllowedTo('view partner')" class="nav-item">
                <router-link :to="{ name: 'partner' }"
                             event="click"
                             class="nav-link d-flex align-items-center justify-content-center"
                             >
                  <fa-icon class="fa-lg fa-fw" icon="user-friends"></fa-icon>
                  <span class="ms-2">Trauerfreunde</span></router-link>
              </li>
              <li v-else class="nav-item">
                <router-link :to="{ name: 'partner' }" :disabled="true"
                             class="nav-link d-flex align-items-center justify-content-center nav-item--disabled">
                  <fa-icon class="fa-lg fa-fw" icon="user-friends"></fa-icon>
                  <span class="ms-2">Trauerfreunde</span></router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'profile' }" class="nav-link d-flex align-items-center justify-content-center">
                  <fa-icon class="fa-lg fa-fw" icon="user"></fa-icon>
                  <span class="ms-2">Profil</span>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'chat' }" class="nav-link d-flex align-items-center justify-content-center">
                                            <span class="msgCount" v-if="unreadCount > 0">
                                                {{ unreadCount }}
                                            </span>
                  <fa-icon v-else class="fa-lg fa-fw" icon="envelope"></fa-icon>
                  <span class="ms-2" v-if="unreadCount === 0">Nachrichten</span>
                  <span class="ms-2" v-else-if="unreadCount === 1">Nachricht</span>
                  <span class="ms-2" v-else>Nachrichten</span>
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'settings' }" class="nav-link d-flex align-items-center justify-content-center" exact>
                  <fa-icon class="fa-lg fa-fw" icon="cogs"></fa-icon>
                  <span class="ms-2">Einstellungen</span></router-link>
              </li>
              <li class="nav-item only--mobile" v-if="!isPremium">
                <router-link :to="{ name: 'premium' }" class="nav-link d-flex align-items-center justify-content-center">
                  <fa-icon class="fa-lg fa-fw" icon="puzzle-piece"></fa-icon>
                  <span class="ms-2">Mitglied werden</span>
                </router-link>
              </li>
            </template>
          </ul>
          <!-- Social menu -->
          <div class="social-navigation d-none d-lg-flex">
            <ul>
              <li>
                <a href="https://www.facebook.com/TrostHelden-108679684045740/" target="_blank">
                  <fa-icon :icon="['fab', 'facebook-square']" class="icon"></fa-icon>
                </a>
              </li>
              <li>
                <a href="https://www.instagram.com/trosthelden_official/" target="_blank">
                  <fa-icon :icon="['fab', 'instagram']" class="icon"></fa-icon>
                </a>
              </li>
              <li>
                <span v-if="isLoggedIn" @click="onLogout()" class="nav-link nav-link--logout d-flex align-items-center justify-content-center"
                      style="cursor: pointer">Abmelden
                </span>
              </li>
              <li>
                <router-link v-if="!isLoggedIn" class="nav-link d-flex align-items-center justify-content-center" :to="{ name: 'login' }"
                             >Login
                </router-link>
              </li>
              <li>
                <router-link v-if="!isLoggedIn" :to="{ name: 'register' }" class="nav-link nav-link--logout d-flex align-items-center justify-content-center"
                             >Kostenlos anmelden
                </router-link>
              </li>
            </ul>
          </div>
        </div>
        <div class="d-lg-none d-xl-none">
          <hr v-if="isLoggedIn" class="divider"/>
          <ul class="navbar-nav ms-md-auto text-center">
            <template v-if="!isLoggedIn">
              <li class="nav-item">
                <router-link :to="{ name: 'login' }" class="nav-link d-flex align-items-center justify-content-center" >
                  Login
                </router-link>
              </li>
              <li class="nav-item">
                <router-link :to="{ name: 'register' }" class="nav-link d-flex align-items-center justify-content-center" >
                  Kostenlos anmelden
                </router-link>
              </li>
            </template>
            <template v-if="isLoggedIn">
              <li class="nav-item">
                <span @click="onLogout" class="nav-link nav-link--logout d-flex align-items-center justify-content-center">
                  Abmelden
                </span>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </nav>
    <nav v-if="subNavigationComponent !== null" class="sub">
      <component :is="subNavigationComponent"></component>
    </nav>
  </header>
</template>

<script>
import FullLogo from "./FullLogo";
import PartnerNavigation from "./partner/PartnerNavigation";
import SettingsNavigation from "./settings/SettingsNavigation";
import {mapGetters, mapState} from "vuex";
import PartnerHelpers from "../helpers/PartnerHelpers";

export default {
  name: "PageHeader",
  components: {FullLogo},
  methods: {
    onLogout() {
      PartnerHelpers.clearPartnerListCache();
      this.$store
          .dispatch("currentUser/logout")
          .then(() => this.$router.push({path: "/login"}));
    },
  },
  computed: {
    subNavigationComponent() {
      if (
          this.$route.matched.some(
              ({name}) => name === "partner-category"
          )
      ) {
        return PartnerNavigation;
      } else if (
          this.$route.matched.some(
              ({name}) => name === "settings-category"
          )
      ) {
        return SettingsNavigation;
      }
      return null;
    },
    ...mapGetters("currentUser", {
      isLoggedIn: "isLoggedIn",
      currentUser: "getObject",
      isPremium: "isPremium",
      isBusinessAccount: "isBusinessAccount",
      isAllowedTo: "isAllowedTo"
    }),
    ...mapState("chat", {
      unreadCount: "unreadCount",
    }),
  },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.navbar {
  align-items: flex-end !important;

  .trosthelden-logo img {
    max-height: 28px;
  }
}

.nav-wrapper {
  position: relative;

  div.counter {
    font-size: 12px;
    font-weight: 600;
    display: none;
    position: absolute;
    bottom: 1px;
    right: 4px;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 1rem;
    border: 3px solid $brand-color-primary;
    background: $brand-color-base;
    color: $brand-color-primary;
    align-items: center;
    justify-content: center;
    margin-left: auto;

    @media screen and (max-width: 991px) {
      display: flex;
    }
  }
}

.header {
  display: flex;
  flex-direction: column;
  background: $brand-color-base;
  margin-bottom: 2.25rem;
  box-shadow: 0 1px 5px 1px rgb(0, 0, 0, 0.34);

  * {
    hyphens: manual;
    -webkit-hyphens: manual;
    -ms-hyphens: manual;
  }

  @media (max-width: 992px) {
    margin-bottom: 1.25rem;
    height: 100%;
    z-index: 99;
  }
}

.main-navigation.navbar {
  background-color: $brand-color-primary;
  padding: 2rem 1rem 2rem 1rem;


  .trosthelden-logo {
    padding-bottom: .5rem;
    max-width: 234px;
  }

  @media (min-width: 992px) {
    padding: 3rem 3rem 1.5rem 3rem;
  }
}


.navbar-collapse {
  @media (min-width: 992px) {
    flex-basis: 100%;
  }

  &.show,
  &.collapsing {
    background-color: $brand-color-primary;
  }

  .navbar-nav__container {
    @media (min-width: 992px) {
      flex: auto;
    }
  }

  .navbar-nav {
    background: $brand-color-primary;

    .nav-item {
      padding: 0 0.125rem;

      @media (max-width: 992px) {
        padding: 0;
        margin: 0.1rem;
        line-height: 60px;
      }

      &--disabled {
        opacity: 40%;
      }

      .nav-link {
        color: $brand-color-base;
        font-weight: 600;

        &:last-child {
          padding-right: 0;
        }

        &--logout {
          cursor: pointer;
          color: $brand-color-base;
        }

        @media (min-width: 992px) {
          display: flex;
        }

        @media (max-width: 992px) {
          padding: 0 0.625rem;
        }

        &.router-link-active.router-link-exact-active {
          color: map-get($brand-colors, "blue-100");

          .fa-fw {
            color: map-get($brand-colors, "blue-100");
          }
        }

        .fa-fw {
          color: $brand-color-base;
        }
      }
    }
  }
}

.nav-link--logout {
  cursor: pointer;
  color: $brand-color-base;
}


.social-navigation {
  order: -1;
  display: flex;
  flex-basis: 100%;
  justify-content: flex-end;
  font-size: 0.625rem;
  text-align: right;
  text-transform: uppercase;
  margin: 0.5rem 0;

  ul {
    display: inherit;
    flex-direction: row;
    list-style: none;
    margin: 0;
    padding: 0;

    li {
      display: inherit;
      align-content: center;
      justify-content: center;

      > a {
        color: map-get($brand-colors, "blue-100");
        padding: 0 0.5rem;
      }

      .icon {
        font-size: 1rem;
      }
    }
  }
}

.sub {
  padding: 0.35rem;
  background-color: $brand-color-highlight;

  @media only screen and (min-width: 768px) {
    padding: 0.35rem 1.5rem;
  }

  & ::v-deep {
    & ul {
      list-style: none;
      color: $brand-color-primary;
      display: flex;
      font-size: 0.75rem;
      margin-bottom: 0;
      padding: 0;
      flex-wrap: wrap;

      @media only screen and (min-width: 576px) {
        padding: 0 15px;
      }

      @media only screen and (min-width: 768px) {
        font-size: 1rem;
      }

      & > li {
        &:not(:last-child)::after {
          content: "|";
          margin: 0 0.4rem;
        }

        & > a {
          color: $brand-color-primary;

          &.router-link-active {
            color: $brand-color-base;
          }
        }
      }
    }
  }
}

.flex-basis-100 {
  flex-basis: 100%;
}

.flex-auto {
  flex: auto;
}

.divider {
  border: 1px solid $brand-color-primary;
}

.msgCount {
  width: 1.4rem;
  height: 1.4rem;
  border-radius: 1rem;
  border: 3px solid $brand-color-primary;
  background: $brand-color-base;
  color: $brand-color-primary;
  font-size: 12px;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-menu {
  width: 2.25rem;
  height: 2.25rem;
}
</style>
