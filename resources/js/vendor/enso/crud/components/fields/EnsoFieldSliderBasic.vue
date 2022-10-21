<template>
  <div :class="allFieldsetClasses">
      <label
          :for="id_attr"
          :class="labelClasses"
          v-if="label"
      >
          <slot name="label">{{ label }}</slot>
      </label>
      <p :class="controlClasses">
        <slot>
          <div class="columns">
            <div class="column">
              <input
                class="input"
                type="range"
                :id="id_attr"
                :value="value"
                :min="options.min"
                :max="options.max"
                :step="options.step"
                :disabled="disabled"
                :readonly="readonly"
                @input="updateValue(parseFloat($event.target.value))"
              >
            </div>
            <div class="column is-1">
              <div class="button is-light range-value">
                {{ value }}
              </div>
            </div>
          </div>
        </slot>
       <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
       <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
     </p>
  </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';

  export default {
    mixins: [
      BaseInput
    ],

    props: {
      options: {
        default() {
          return {};
        }
      },
      value: {
        default() {
          return 0;
        }
      }
    },

    created() {
      // this.options = this.field.options;
    },
  }
</script>
