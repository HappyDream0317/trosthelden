<template>
  <div class="input-tools">
    <template v-if="!disabled">
      <div class="input-tools__emoji">
        <emoji-picker v-if="emojiPicker" @select="appendEmoji" class="emoji-picker" :disable-skin-tones="true"
                      :hide-search="true" :hide-group-names="true" theme="dark"/>
        <button
            class="btn"
            @click.stop="toggleEmojiPicker"
            @touch.stop="toggleEmojiPicker"
        >
          <fa-icon :icon="['far', 'smile']"></fa-icon>
        </button>
      </div>


      <ValidationProvider
          as="div"
          class="input-message  input-tools__message"
          rules="max:8000"
          v-model="message"
          v-slot="{ errorMessage, field }"
          :validateOnModelUpdate="false"
          :validateOnInput="true"
      >
        <div class="form-control input-message__text">
                    <textarea
                        type="text"
                        @click="onTextareaClick"
                        :placeholder="
                            disabled ? disabled : 'Nachricht eingeben'
                        "
                        v-bind="field"
                        :disabled="disabled"
                    ></textarea>
        </div>
        <div class="input-message__bottom">
          <div>
                        <span
                            v-if="errorMessage"
                            class="validation-max-length"
                        >
                            {{ errorMessage }}
                        </span>
          </div>
          <div class="limiter">
            {{ charactersLeft }} / 8000 Zeichen
            <Tooltip
                addClass="chat-message"
                :content="`Wir haben an dieser Stelle durch verschiedene Erfahrungen eine Zeichenbegrenzung eingerichtet. Hier und da machen lange Nachrichten jedoch großen Sinn, z.B. wenn Trauerfreunde sich als gegenseitige Tagebuchleser verabreden. Für diese o.ä. Fälle bitten wir darum, Nachrichten in mehreren Teilen zu senden.`"
            >
            </Tooltip>
          </div>
        </div>
      </ValidationProvider>

      <button
          class="btn submit  input-tools__send"
          @click="sendMessage"
          :disabled="disabled"
          title="Senden"
      >
        <fa-icon icon="paper-plane"></fa-icon>
      </button>
    </template>
    <template v-else>
      {{ disabled }}
    </template>
  </div>
</template>

<script>
import EmojiPicker from 'vue3-emoji-picker'
import 'vue3-emoji-picker/css';
import Tooltip from "../Tooltip";
import {mapGetters} from "vuex";

const categoryTranslations = {
  "Frequently used": "Beliebt",
  People: "Menschen",
  Nature: "Natur",
  Objects: "Objekte",
  Places: "Orte",
  Symbols: "Symbole",
};

export default {
  name: "MessageInput",
  components: {
    Tooltip,
    EmojiPicker,
  },
  props: {
    disabled: {
      type: String,
    },
  },
  watch: {
    activeChat: function (newVal, oldVal) {
      this.message = null;
    },
  },
  computed: {
    charactersLeft() {
      if (this.message) {
        return this.message.length > 8000 ? 8000 : this.message.length;
      }
      return 0;
    },
    display() {
      const body = document.documentElement;
      const top = ((window.scrollY || body.scrollTop) - (body.clientTop || 0) + (body.offsetHeight / 2));

      return {
        top: `calc(${top || 0}px  - 20rem)`,
        left: `calc((${body.offsetWidth || 0}px - 15rem) / 2)`
      }
    },
    ...mapGetters("currentUser", {
      currentUserId: "getId",
    }),
    ...mapGetters("chat", {
      activeChat: "activeChat",
      activeChatPartnerId: "activeChatPartnerId",
    }),
  },
  data: () => ({
    message: "",
    loading: false,
    emojiPicker: false
  }),
  methods: {
    toggleEmojiPicker() {
      this.emojiPicker = !this.emojiPicker;
    },
    async onTextareaClick() {
      await this.$store.dispatch("chat/markAsRead");
    },
    translateCategory(category) {
      return categoryTranslations[category];
    },
    appendEmoji(emoji) {
      this.message += emoji.i;
    },
    sendMessage() {
      if (!this.message || this.message.length > 8000) {
        return;
      }
      let message = this.message;

      this.$store
          .dispatch("chat/sendMessage", {
            activeChat: this.activeChat,
            message: message,
            partnerId: this.activeChatPartnerId,
          })
          .then((data) => {
            this.message = "";
            this.$emit("newMessage", {
              ...data,
              message: message,
              user_id: this.currentUserId,
              partnerId: this.activeChatPartnerId,
            });
          })
          .catch((err) => {
            console.log(err);
            this.errored = true;
          })
          .finally(() => {
            this.loading = false;
          });
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../../sass/setup/variables";

.input-tools {
  padding: 0.625rem 1rem 0.625rem 0.625rem;
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;

  & > * {
    margin: 0.625rem 0;
  }

  &__emoji {
    padding: 0px !important;
  }

  @media only screen and (max-width: 768px) {
    &__message {
      order: 1;
      flex: auto !important;
      width: 100%;
    }
    &__emoji {
      order: 2;
    }
    &__send {
      order: 3;
    }
    &__emoji, &__send {
      margin-top: 0px;
    }
  }
}

.actions {
  flex-basis: 100%;
  margin: 0;
}

.input-message {
  flex: 1;

  &__text {
    max-width: 606px;
    min-height: 222px;
    margin-right: 0.75rem;

    textarea {
      outline: 0;
      width: 100%;
      border: 0;
      height: 100%;
      resize: both;
    }
  }

  &__bottom {
    margin-top: 6px;
    @media (min-width: 768px) {
      display: flex;
      justify-content: space-between;
    }

    div,
    span {
      font-size: 0.8rem;
      line-height: 1rem;
      text-align: right;
      @media (min-width: 768px) {
        text-align: left;
      }
    }

    .validation-max-length {
      font-style: italic;
    }
  }
}

button {
  border: none;
  background: none;
  color: $brand-color-primary;
  font-size: 1.2rem;
  cursor: pointer;
}

button[disabled] {
  color: $dark-grey-text;
}

.wrapper {
  position: relative;
  display: inline-block;
}

.input-tools__emoji {
  position: relative;

}

.emoji-picker {
  position: absolute;
  z-index: 10;
  border: 1px solid #ccc;
  color: $light-grey-text;
  width: 15rem;
  height: 20rem;
  overflow-y: hidden;
  padding: 0;
  top: 50%;
  left: 100%;
  transform: translateY(-50%);
  box-sizing: border-box;
  border-radius: 0.5rem;
  background: $brand-color-primary;

  .v3-emojis {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;

    &:after {
      content: "";
      flex: auto;
    }

    button {
      padding: 0.2rem;
      cursor: pointer;
      border-radius: 5px;

      &:hover {
        background: $brand-color-secondary;
        cursor: pointer;
      }
    }
  }
}


</style>
