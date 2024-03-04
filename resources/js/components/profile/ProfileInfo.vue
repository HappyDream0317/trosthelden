<template>
    <div v-if="hasLoaded" class="p-4 root">
        <h3 class="d-flex">
            <span class="text-center w-25px flex-shrink-0"
                ><fa-icon icon="user" /></span
            ><span class="ps-3 color--primary font-weight-not-bold h6"
                >Persönliche Angaben</span
            >
        </h3>
        <div class="d-flex mb-1">
            <div class="text-center w-25px flex-shrink-0">
                <fa-icon icon="user" />
            </div>
            <div class="ps-3">
                <UserIdentifier :user="currentUser" />
            </div>
        </div>
        <div class="mb-1 d-flex">
            <div class="text-center w-25px flex-shrink-0">
                <fa-icon icon="home" />
            </div>
            <div class="ps-3">{{ location }}</div>
        </div>
        <div class="d-flex mb-1">
            <div class="text-center w-25px">
                <fa-icon icon="link" />
            </div>
            <div class="ps-3">{{ info.marital_status }}</div>
        </div>
        <ProfileInfoJob
            v-if="show_job"
            :visibleJob="visibleJob"
            :job="info.job"
        />
        <ProfileInfoChildren
            :visibleChildren="visibleChildren"
            :number_of_children="info.number_of_children"
            :children_in_household="info.children_in_household"
            :children_affected="info.children_affected"
        />
        <div v-if="isPremium" class="d-flex">
            <div class="text-center w-25px flex-shrink-0">
                <fa-icon icon="star" />
            </div>
            <div class="ps-3">Du bist Premium Nutzer</div>
        </div>
        <div class="d-flex mt-4">
            <div class="text-center w-25px flex-shrink-0">
                <fa-icon icon="info-circle" />
            </div>
            <div class="ps-3">
                Hinweis: Mit den (<fa-icon icon="eye" class="color--grey" /> /
                <fa-icon icon="eye-slash" class="color--grey" />)-Icons kannst
                du einstellen, ob andere Mitglieder diese Information auf deinem
                Profil sehen können.
            </div>
        </div>
    </div>
</template>

<script>
import InfoVisibilityToggle from "./profile_info/InfoVisibilityToggle";
import UserIdentifier from "../UserIdentifier";
import ProfileInfoChildren from "./profile_info/ProfileInfoChildren";
import ProfileInfoReligion from "./profile_info/ProfileInfoReligion";
import ProfileInfoJob from "./profile_info/ProfileInfoJob";
import { mapGetters } from "vuex";

export default {
    components: {
        ProfileInfoJob,
        ProfileInfoReligion,
        ProfileInfoChildren,
        UserIdentifier,
        InfoVisibilityToggle,
    },
    props: ["tooltip_text", "info", "visibilities"],
    name: "ProfileInfo",
    data: function () {
        return {
            isPremium: false,
            hasLoaded: false,

            visibleJob: this.visibilities.job,
            visibleReligion: this.visibilities.religion,
            visibleChildren: this.visibilities.children,
        };
    },
    async mounted() {
        this.hasLoaded = true;
        this.isPremium = await this.fetchIsPremium();
    },

    computed: {
        show_job() {
            if (this.hasLoaded) {
                return this.info.job !== "1";
            }
            return false;
        },
        location() {
            let location = this.info.country;
            if (location !== null && location !== "") location += ", ";
            return location + this.info.postal;
        },
        ...mapGetters("currentUser", {
            // isPremium: "isPremium",
            currentUser: "getObject",
        }),
    },
    methods: {
        updateVisibilities(params) {
            axios.put("/api/profile/visibility/set", params).catch((err) => {
                console.log(err);
            });
        },
        fetchIsPremium() {
            return axios
                .get("/api/user/premium")
                .then(({ data }) => data.isPremium)
                .catch((err) => {
                    console.error(err);
                });
        },
    },
};
</script>

<style scoped lang="scss">
@import "../../../sass/setup/variables";

.root {
    color: #7f7f7f;
}
::v-deep .svg-inline--fa {
    color: $brand-color-primary;
}

/* Tooltip text */
.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: $brand-color-highlight;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;

    /* Position the tooltip text */
    position: absolute;
    z-index: 1;
    bottom: 125%;
    left: 50%;
    margin-left: -60px;

    /* Fade in tooltip */
    opacity: 0;
    transition: opacity 0.3s;
}

/* Tooltip arrow */
.tooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}
</style>
