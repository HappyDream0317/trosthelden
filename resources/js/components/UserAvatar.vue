<template>
  <img
      v-if="hasAvatar"
      class="avatar"
      :src="avatar"
      @click="gotoProfile"
      alt="Benutzer Profilbild"
  />
  <avatar-placeholder v-else className="avatar" @click="gotoProfile"/>
</template>

<script>
import AvatarPlaceholder from "./AvatarPlaceholder";
import {mapGetters} from "vuex";

export default {
  name: "UserAvatar",
  components: {AvatarPlaceholder},
  props: {
    user: Object,
  },
  methods: {
    gotoProfile() {
      if (this.user && this.user.nickname !== null) {
        this.$router
            .push({
              name: "foreignProfile",
              params: {user_id: this.user.id},
            })
            .catch(() => null);
      }
    },
  },
  computed: {
    hasAvatar() {
      return (
          this.user &&
          this.user.hasOwnProperty("avatar") &&
          this.user.avatar !== null
      );
    },
    avatar() {
      if (this.hasAvatar) {
        return `${window.location.origin}/${this.user.avatar}`;
      }
      return '';
    },
  }
};
</script>

<style scoped lang="scss">
.avatar {
  display: block;
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 50%;
  margin-bottom: 0.625rem;
  cursor: pointer;
}

#foreign-profile .avatar {
  @media screen and (min-width: 768px) {
    //position: absolute;
    width: 5rem;
    height: 5rem;
  }
  @media screen and (min-width: 1200px) {
    width: 6rem;
    height: 6rem;
  }
}
</style>
