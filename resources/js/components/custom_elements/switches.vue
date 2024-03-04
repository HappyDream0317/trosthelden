<template>
  <label :class="classObject">
    <span class="vue-switcher__label" v-if="shouldShowLabel">
        <span v-if="label" v-text="label"></span>
        <span v-if="!label && data" v-text="textEnabled"></span>
        <span v-if="!label && !data" v-text="textDisabled"></span>
    </span>
    <input
        type="checkbox"
        :disabled="disabled"
        v-model="data"
    />
    <div></div>
  </label>
</template>

<script>
export default {
  name: "switches",
  props: {
    typeBold: {
      default: false,
    },
    modelValue: {
      default: false,
    },
    disabled: {
      default: false,
    },
    label: {
      default: "",
    },
    textEnabled: {
      default: "",
    },
    textDisabled: {
      default: "",
    },
    color: {
      default: "default",
    },
    theme: {
      default: "default",
    },
    emitOnMount: {
      default: true,
    },
  },
  mounted() {
    if (this.emitOnMount) {
      this.$emit("update:modelValue", this.modelValue);
    }
  },
  data() {
    return {
      data: this.modelValue,
    };
  },
  computed: {
    classObject() {
      return {
        "vue-switcher": true,
        ["vue-switcher--unchecked"]: !this.data,
        ["vue-switcher--disabled"]: this.disabled,
        ["vue-switcher--bold"]: this.typeBold,
        ["vue-switcher--bold--unchecked"]: this.typeBold && !this.data,
        [`vue-switcher-theme--${this.theme}`]: this.color,
        [`vue-switcher-color--${this.color}`]: this.color,
      };
    },
    shouldShowLabel() {
      return (
          this.label !== "" ||
          this.textEnabled !== "" ||
          this.textDisabled !== ""
      );
    },
  },
  watch: {
    modelValue() {
      if (this.data !== this.modelValue) {
        this.data = this.modelValue;
      }
    },
    data() {
      if (this.data !== this.input_text) {
        this.$emit("update:modelValue", this.data);
        this.$emit("change");
      }
    },
  },
};
</script>

<style lang="scss">
@import "../../../sass/setup/variables";

$color-default: $brand-color-primary;
$color-primary: #337ab7;
$color-success: #5cb85c;
$color-info: #5bc0de;
$color-warning: #f0ad4e;
$color-danger: #c9302c;

$theme-default-colors: (
    default: $color-default,
    primary: $color-primary,
    success: $color-success,
    info: $color-info,
    warning: $color-warning,
    danger: $color-danger,
);

.vue-switcher {
  position: relative;
  display: inline-block;

  &__label {
    display: block;
    font-size: 10px;
    margin-bottom: 5px;
  }

  input {
    opacity: 0;
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 1;
    cursor: pointer;
  }

  div {
    height: 15px;
    width: 36px;
    position: relative;
    border-radius: 30px;
    display: -webkit-flex;
    display: -ms-flex;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    cursor: pointer;
    transition: linear 0.2s, background-color linear 0.2s;

    &:after {
      content: "";
      height: 20px;
      width: 20px;
      border-radius: 100px;
      display: block;
      transition: linear 0.15s, background-color linear 0.15s;
      position: absolute;
      left: 100%;
      margin-left: -18px;
      cursor: pointer;
      top: -3px;
      box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.1);
    }
  }

  &--unchecked {
    div {
      justify-content: flex-end;

      &:after {
        left: 15px;
      }
    }
  }

  &--disabled {
    div {
      opacity: 0.3;
    }

    input {
      cursor: not-allowed;
    }
  }

  &--bold {
    div {
      top: -8px;
      height: 26px;
      width: 51px;

      &:after {
        margin-left: -24px;
        top: 3px;
      }
    }

    &--unchecked {
      div {
        &:after {
          left: 28px;
        }
      }
    }

    .vue-switcher__label {
      span {
        padding-bottom: 7px;
        display: inline-block;
      }
    }
  }

  &-theme--default {
    @each $colorName, $color in $theme-default-colors {
      &.vue-switcher-color--#{$colorName} {
        div {
          @if $colorName == "default" {
            background-color: $color;
          } @else {
            background-color: lighten($color, 10%);
          }

          &:after {
            @if $colorName == "default" {
              background-color: lighten($color, 5%);
            } @else {
              background-color: $color;
            }
          }
        }

        &.vue-switcher--unchecked {
          div {
            background-color: white;
            border: 1px solid $color;

            &:after {
              background-color: $light-grey;
              border: 1px solid $color;
            }
          }
        }
      }
    }
  }
}
</style>
