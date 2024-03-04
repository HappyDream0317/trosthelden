<template>
  <keep-alive>
    <div class="comment-wrapper">
      <!-- comment -->
      <div class="comment">
        <div class="comment__reply" v-if="depth > 0">
          <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 25 25"
              width="25"
              height="25"
              id="replySvg"
          >
            <path
                id="reply"
                class="shp0"
                d="M7.3 6.43L7.3 8.42C7.09 8.42 6.9 8.46 6.73 8.54C6.54 8.62 6.37 8.73 6.22 8.87C6.08 9 5.97 9.17 5.9 9.37C5.82 9.55 5.78 9.75 5.78 9.96C5.78 10.16 5.82 10.35 5.9 10.53C5.99 10.75 6.1 10.92 6.22 11.04C6.37 11.18 6.54 11.29 6.73 11.36C6.9 11.44 7.09 11.48 7.3 11.48L18.07 11.48L15.34 8.75L16.75 7.34L21.35 11.95L21.35 13L16.75 17.61L15.34 16.2L18.07 13.47L7.3 13.47C6.79 13.47 6.35 13.39 5.97 13.21C5.51 13.01 5.13 12.75 4.83 12.44C4.48 12.08 4.22 11.7 4.06 11.29C3.88 10.87 3.8 10.43 3.8 9.96C3.8 9.48 3.88 9.03 4.06 8.61C4.23 8.16 4.49 7.78 4.83 7.46C5.2 7.11 5.57 6.85 5.97 6.69C6.37 6.52 6.82 6.43 7.3 6.43Z"
            />
          </svg>
        </div>
        <div class="comment__user-info">
          <UserAvatar v-if="author" :user="author"/>
          <div class="username" style="margin-right: auto">
                        <span class="comment__title color--primary font-bold">
                            {{
                            author.nickname
                                ? author.nickname
                                : "Ehemaliges Mitglied (" + author.id + ")"
                          }}
                            schreibt:
                        </span>
            <span v-if="depth >= 1 && title" class="h2">{{
                title
              }}</span>
            <span v-if="depth >= 1" class="timestamp">{{
                this.getTimeStamp(date)
              }}</span>
          </div>
          <span
              class="status-symbols status-symbols--header"
              v-if="depth <= 0"
          >
                        <span>
                            <fa-icon class="icon" icon="eye"/>
                            {{ views }}
                        </span>
                        <span>
                            <fa-icon class="icon" icon="comment"/>
                            {{ comments }}
                        </span>
                    </span>

          <UserActions
              v-if="
                            currentUserID !== author.id &&
                            isAllowedTo('view user_actions')
                        "
              :user="author"
          />
        </div>
        <div class="comment__content">
          <div class="comment__content-header">
            <div>
                            <span class="comment__title color--primary font-bold">
                                {{
                                author.nickname
                                    ? author.nickname
                                    : "Ehemaliges Mitglied (" +
                                    author.id +
                                    ")"
                              }}
                                schreibt:
                            </span>
              <span class="h2" v-if="title">{{ title }}</span>
              <span class="timestamp">{{
                  this.getTimeStamp(date)
                }}</span>
            </div>
            <span class="status-symbols" v-if="depth <= 0">
                            <span>
                                <fa-icon class="icon" icon="eye"/>
                                {{ views }}
                            </span>
                            <span>
                                <fa-icon class="icon" icon="comment"/>
                                {{ comments }}
                            </span>
                        </span>
          </div>
          <div class="comment__content-body">
            <p v-html="content"></p>
          </div>
          <div class="comment__content-footer">
            <PostActions
                @report="emitReportEvent"
                @reply="emitReplyEvent(id)"
                @quote="emitQuoteEvent(id)"
            />
          </div>
        </div>
      </div>
      <!-- end of comment -->

      <!-- replies -->
      <div class="replies">
        <Comment
            v-for="reply in replies"
            :key="'r-' + reply.id"
            :author="reply.author"
            :title="reply.title"
            :content="reply.comment"
            :location="reply.location"
            :replies="reply.comments"
            :date="reply.created_at"
            :depth="1"
            :currentUserID="currentUserID"
            @report="emitReportEvent(reply.id)"
            @reply="emitReplyEvent(reply.id)"
            @quote="emitQuoteEvent"
        />
      </div>
      <!-- end of replies -->
    </div>
  </keep-alive>
</template>

<script>
import UserActions from "../groups/UserActions";
import UserAvatar from "../UserAvatar";
import UserIdentifier from "../UserIdentifier";
import formatDate from "../../utils/format-date";
import PostActions from "../groups/PostActions";
import {mapGetters} from "vuex";

export default {
  name: "Comment",
  components: {PostActions, UserIdentifier, UserAvatar, UserActions},
  props: {
    author: Object,
    location: String,
    title: String,
    date: String,
    content: String,
    views: Number,
    comments: Number,
    replies: Array,
    currentUserID: Number,
    itemId: Number,
    id: Number,
    depth: {
      default: 0,
    },
  },
  methods: {
    getTimeStamp() {
      return formatDate(this.date);
    },
    onGoToProfile(userId) {
      this.$router.push({
        name: "foreignProfile",
        params: {user_id: userId},
      });
    },
    emitReportEvent(id) {
      if (id === undefined) {
        id = this.id;
      }

      this.$emit("report", id);
    },

    emitReplyEvent(id) {
      const parentId = this.getParentId(id);
      this.$emit("reply", parentId);
    },

    emitQuoteEvent(quote) {
      if (typeof quote === "undefined") {
        quote = {
          id: this.getParentId(),
          quote: {
            user: this.author,
            comment: this.content,
            date: this.created,
          },
        };
      }
      this.$emit("quote", quote);
    },
    getParentId(id) {
      if (id === undefined && this.isMaxDepth) {
        id = this.fatherId;
      } else if (typeof id === "undefined") {
        id = this.id;
      }
      return id;
    },
  },
  computed: {
    ...mapGetters("currentUser", {
      currentUser: "getObject",
      isAllowedTo: "isAllowedTo"
    }),
  },
};
</script>

<style lang="scss">
@import "./resources/sass/setup/variables";

.comment-wrapper {
  .comment {
    display: flex;
    flex-direction: column;
    background: #ffffff;
    border-radius: 0.5rem;
    border: none;
    max-height: 100%;
    padding: 0.625rem;

    @media only screen and (min-width: 768px) {
      flex-direction: row;
    }

    &:first-child {
      border-bottom-left-radius: 0;
      border-bottom-right-radius: 0;
    }

    &__reply {
      flex: 0 1 100%;
      @media only screen and (min-width: 768px) {
        flex: 0 1 5%;
        padding: 10px 5px;
        margin-top: 3rem;
      }
    }

    &__user-info {
      flex: 0 1 100%;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;

      .username {
        @media only screen and (min-width: 768px) {
          display: none;
        }
      }

      @media only screen and (min-width: 768px) {
        flex-direction: column;
        flex: 0 1 10%;
        margin-bottom: 0;
        justify-content: flex-start;
      }

      .comment__title {
        display: block;
        font-size: 15px;
      }

      .avatar {
        margin-right: 0.5rem;
        max-width: 2.5rem;
        max-height: 2.5rem;
        margin-bottom: 10px;
        @media only screen and (min-width: 768px) {
          margin-right: 0;
          max-width: 3.5rem;
          max-height: 3.5rem;
        }
      }

      .comment__user-meta {
        margin-right: 0.5rem;
        font-size: 1rem;
        display: none;
      }

      .user-actions {
        flex-direction: column;
        align-items: center;
        width: 100%;
        display: none;

        @media only screen and (min-width: 768px) {
          display: flex;
        }

        button.btn {
          border: none;
          margin: 0;
          background: none;
          color: $primary;
          text-align: center;
          display: flex;
          align-items: center;
          justify-content: center;
          width: 100%;
          padding: 8px !important;

          .icon {
            margin-right: 0;
          }
        }
      }
    }

    &__content {
      flex: 0 1 100%;
      padding: 0;
      display: flex;
      flex-direction: column;

      @media only screen and (min-width: 768px) {
        padding: 0 1rem;
      }

      &-header {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 5px;
        //display: none;

        @media only screen and (min-width: 768px) {
          margin-bottom: 0;
          display: flex;
        }

        div {
          display: flex;
          flex-direction: column;
        }
      }

      &-footer {
        margin-top: auto;

        & > div button {
          padding: 0;
        }
      }
    }

    &__title {
      display: none;
      font-size: 1rem;

      @media only screen and (min-width: 768px) {
        display: block;
      }
    }

    .status-symbols {
      display: none;
      align-items: center;
      justify-content: center;
      color: $primary;
      @media only screen and (min-width: 768px) {
        display: flex;
        flex-direction: column;
        align-self: baseline;
      }

      &--header {
        display: flex;
        @media only screen and (min-width: 768px) {
          display: none;
        }

        span {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;

          &:first-child {
            margin-right: 10px;
          }

          .icon {
            margin-right: 0;
          }

          @media only screen and (min-width: 768px) {
            display: unset;
          }
        }
      }
    }
  }
}

.replies {
  background: #cdcdcd;
  padding: 5px;

  &:last-child {
    padding-bottom: 0;
  }

  &:empty {
    padding: 0;
  }

  .comment-wrapper {
    padding: 5px;

    .comment {
      &:first-child {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
      }

      &__user-info {
        margin-right: auto;
      }

      &__content {
        &-header {
          //display: none;
        }
      }
    }
  }

  .replies {
    padding: 0 0 0 1rem;

    .comment-wrapper {
      padding: 5px 0;

      &:first-child {
        padding-bottom: 1px;
      }
    }
  }
}
</style>
