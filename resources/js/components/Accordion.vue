<template>
  <div class="accordion" :id="'accordion' + id">
    <div class="accordion-item card rounded mb-2">
      <div class="accordion-header card-header" :id="'accordion-header' + id">
        <button
            class="accordion-button btn collapsed accordion_row__trigger"
            type="button"
            data-bs-toggle="collapse"
            :data-bs-target="'#accordion_collapse' + id"
            aria-expanded="false"
            :aria-controls="'accordion_collapse' + id"
        >
          {{ label }}
        </button>
      </div>
      <div
          :id="'accordion_collapse' + id"
          class="accordion-collapse collapse pt-2"
          :aria-labelledby="'accordion-header' + id"
          :data-bs-parent="'#accordion' + id"
      >
        <div class="accordion-body card-body">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Accordion",
  props: {
    id: Number,
    label: String,
  },
};
</script>

<style lang="scss" scoped>
@import "../../sass/setup/variables";
@import "../../sass/micro-clearfix.scss";

.accordion_row__trigger {
  display: flex;
  flex-basis: 12rem;
  flex-grow: 1;
  align-items: center;
  justify-content: space-between;

  position: relative;
  width: 100%;
  min-height: 4rem;

  background-color: $light-grey-background;
  border-radius: $border-radius;

  //margin: 0 0.625rem 0.625rem 0;
  padding: 0.625rem;

  color: $brand-color-primary;
  font-weight: 500;
  text-align: left;

  & .card-handle {
    display: inline-block;
    float: right;
    color: $brand-color-primary;
    font-size: 1.2rem;
    transform-origin: 50% 50%;
    transform: rotate(180deg);
  }

  &.btn:focus {
    box-shadow: none;
  }

  &.collapsed {
    & .card-handle {
      transform: rotate(0deg);
    }
  }
}

.collapse,
.collapsing {
  width: 98%;
  margin-left: auto !important;
  margin-right: auto !important;
}

.card-header {
  background: none;
  border: none;
  line-height: 0.9rem;
  vertical-align: middle;
  padding: 0;

  @include micro-clear;
}

.card {
  border: none;
  overflow: visible;
}
</style>
