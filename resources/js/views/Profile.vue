<template>
  <DefaultLayout>
    <div class="infobox infobox--avatar" v-if="showInfoBox" :key="changeMe">
      <p>
        Tipp: Nutzer mit einem Profilbild erhalten bis zu
        <strong>6 mal mehr Trauerfreund-Anfragen</strong> als Nutzer
        ohne Profilbild.<br/>Auf Platz 2 der wichtigsten
        Profilinformationen folgt der persönliche Spruch.
      </p>

      <p>
        Nimm dir am besten gleich jetzt ein paar Minuten Zeit, um dein
        Profil auszufüllen. Dies ist ein wichtiger Schritt auf dem Weg
        zu deinem Trauerfreund.
      </p>
    </div>
    <p class="color--primary font-weight-not-bold h6">Dein Profil</p>
    <AbstractModal/>
    <div v-if="hasLoaded" class="row">
      <MainContent>
        <ProfilePortrait
            :portrait="profile_portrait"
            :tooltip_text="profile_tooltips.portrait"
            class="rounded-box mb-2"
        />
        <div class="p-3 rounded-box mb-2">
          <p class="color--primary font-weight-not-bold h6">
            Weitere Profilfragen
          </p>
          <p>
            Erzähle ein bisschen mehr über dich. So können sich
            deine Profilbesucher ein noch besseres Bild von dir
            machen. Und beim ersten direkten Kontakt mit deinem
            Trauerfreund kommt ihr viel leichter ins Gespräch.
          </p>

          <ProfileQuestion
              v-for="question in profile_questions"
              :key="question.id"
              v-model="profile_questions_answerStatus[question.id]"
              :answer="question.answer"
              :question="question.question"
              :question_id="question.id"
          />
        </div>
      </MainContent>
      <Sidebar :mobile-on-top="false">
        <template v-if="isAllowedTo('view mourner_profile')">
          <ProfileProgress
              :progress_total="totalProgress"
              class="mb-2 rounded-box"
          />

          <div class="mb-2 rounded-box p-0 d-none d-lg-block">
            <ProfileNewMatchings/>
            <hr class="m-0"/>
            <ProfileNewMessages/>
            <hr class="m-0"/>
            <ProfileNewFriendrequests/>
          </div>
          <ProfileInfo
              v-if="hasLoaded"
              :info="profile_user_info"
              :tooltip_text="profile_tooltips.info"
              :visibilities="profile_visibilities"
              class="mb-2 rounded-box p-0"
          />
          <ProfileReason
              :reason="profile_reason"
              class="mb-2 rounded-highlight-box"
          />
        </template>
        <template v-else>
          <ProfileReason
              reason="SUPPORTER"
              class="mb-2 rounded-highlight-box"
          />
        </template>
      </Sidebar>
    </div>
  </DefaultLayout>
</template>
<script>
import ProfileQuestion from "../components/profile/ProfileQuestion";
import ProfilePortrait from "../components/profile/ProfilePortrait";
import ProfileInfo from "../components/profile/ProfileInfo";
import ProfileNewMatchings from "../components/profile/ProfileNewMatchings";
import ProfileNewMessages from "../components/profile/ProfileNewMessages";
import ProfileReason from "../components/profile/ProfileReason";
import ProfileNewFriendrequests from "../components/profile/ProfileNewFriendrequests";
import ProfileProgress from "../components/profile/ProfileProgress";
import DefaultLayout from "../layouts/DefaultLayout";
import ProfileGroups from "../components/profile/ProfileGroups";
import AbstractModal from "../components/action_icons/AbstractModal";
import AvatarPlaceholder from "../components/AvatarPlaceholder";
import UserAvatar from "../components/UserAvatar";
import MainContent from "../layouts/MainContent";
import Sidebar from "../layouts/Sidebar";
import {mapGetters} from "vuex";

export default {
  components: {
    UserAvatar,
    AvatarPlaceholder,
    AbstractModal,
    ProfileGroups,
    DefaultLayout,
    ProfileProgress,
    ProfileReason,
    ProfileNewMessages,
    ProfileNewMatchings,
    ProfileInfo,
    ProfilePortrait,
    ProfileQuestion,
    ProfileNewFriendrequests,
    MainContent,
    Sidebar,
  },
  data: function () {
    return {
      changeMe: 0,
      showInfoBox: false,
      hasLoaded: false,
      profile_questions: [],
      profile_questions_answerStatus: {},
      profile_portrait: {
        motto: "",
        avatar: "",
      },

      profile_tooltips: {
        portrait: "",
        info: "",
        progress: "",
      },

      profile_reason: {
        text: "",
        death: "",
        year: "",
        relation: "",
      },

      profile_user_info: {
        nickname: "",
        sex: "",
        age: "",
        country: "",
        postal: "",
        marital_status: "",
        job: "",
        religion: "",
        number_of_children: "",
        children_in_household: "",
        children_affected: "",
        matching_step: null,
      },

      profile_visibilities: {
        job: true,
        children: true,
        religion: true,
      },

      profile_groups: {},
    };
  },
  mounted: function () {
    axios
        .get("/api/profile/init")
        .then((response) => {
          const {
            reason,
            groups,
            profile_questions,
            motto,
            tooltips,
            avatar,
            visibilities,
            info,
            mourning
          } = response.data;

          this.profile_questions = profile_questions.sort(
              (a, b) => a.position - b.position
          );

          this.profile_reason = {
            ...this.profile_reason,
            text: mourning.text,
            death: reason.death_reason,
            relation: reason.death_relation,
            year: JSON.parse(reason.death_date).year,
          };

          this.profile_portrait["motto"] = motto;
          this.profile_portrait["avatar"] = avatar;

          this.profile_user_info = {...info};

          this.profile_visibilities = visibilities;
          this.profile_tooltips = tooltips;
          this.profile_groups = groups;
        })
        .catch((err) => {
          console.error(err);
        })
        .finally(() => {
          this.hasLoaded = true;
          this.renderInfoBox();
        });
  },
  computed: {
    calcAnswerProgress() {
      let answer_count = 0;

      Object.keys(this.profile_questions_answerStatus).forEach(
          (answer) => {
            if (this.profile_questions_answerStatus[answer]) {
              answer_count++;
            }
          }
      );

      return answer_count * 30;
    },

    totalProgress() {
      // Picture, Motto, Questions

      let totalProgress = 0;

      if (this.hasLoaded) {
        // frabo counts 40% percent
        totalProgress +=
            String(this.currentUser.matching_step) === "-1" ? 40 : 0;

        // questions count 30%
        totalProgress += this.profileQuestionsProgress() * 30;

        // counts 30% (20% + 10%)
        totalProgress += this.profilePortraitProgress();
      }

      return Math.round(totalProgress);
    },
    ...mapGetters("currentUser", {
      currentUser: "getObject",
      isAllowedTo: "isAllowedTo"
    }),
  },
  methods: {
    profileQuestionsProgress() {
      let answeredProfileQuestionsCount = 0;

      if (this.hasLoaded && this.profile_questions) {
        for (const item of this.profile_questions) {
          if (item === null) {
            continue;
          }

          if (
              typeof item === "object" &&
              item.hasOwnProperty("answer") &&
              typeof item.answer === "string" &&
              item.answer.trim() !== ""
          ) {
            answeredProfileQuestionsCount += 1;
          }
        }
      }
      return (
          answeredProfileQuestionsCount / this.profile_questions.length
      );
    },

    profilePortraitProgress() {
      let answeredProfilePortraitPercentage = 0;

      if (this.hasLoaded && this.profile_portrait) {
        if (this.profile_portrait.motto) {
          answeredProfilePortraitPercentage += 10;
        }

        if (this.profile_portrait.avatar) {
          answeredProfilePortraitPercentage += 20;
        }
      }

      return answeredProfilePortraitPercentage;
    },

    renderInfoBox() {
      if (!this.profile_portrait.avatar) {
        this.showInfoBox = true;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";

.test {
  height: 0;
  overflow: hidden;
}

.test--hidden {
  //transition:visibility 30s ease;
}

.test--show {
  height: 100px;
  transition: all 0.3s ease;
  transition-delay: 30s;
}

.infobox {
  background: #ffffff;
  padding: 1rem;
  border-radius: 0.5rem;
  margin-bottom: 0.5rem;
  color: $brand-color-primary;
  transition: all 0.3s ease;
}
</style>
