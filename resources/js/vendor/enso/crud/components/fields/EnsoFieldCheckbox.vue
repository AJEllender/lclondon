<template>
  <fieldset :class="allFieldsetClasses">
    <p :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </p>
    <p :class="controlClasses">
      <slot>
        <p v-for="(option, index) in options" :key="index" class="control">
          <label class="checkbox">
            <input
              type="checkbox"
              :name="name"
              :value="index"
              :readonly="readonly"
              :disabled="disabled"
              @change="updateValue($event, index)"
              :checked="isChecked(index)"
            />
            {{ option }}
          </label>
        </p>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </p>
  </fieldset>
</template>

<script>
import BaseInput from '../mixins/base-input';
import get from 'lodash/get';
import clone from 'lodash/clone';

export default {
  mixins: [BaseInput],

  props: {
    options: {
      default() {
        return {};
      },
    },
  },

  methods: {
    isChecked: function (name) {
      if (this.isTranslated) {
        return get(this.inputValue, name + '.' + this.language);
      } else {
        return get(this.inputValue, name);
      }
    },

    updateValue($event, name) {
      let value = clone(this.inputValue);
      let new_val = !!$event.target.checked;

      if (!value) {
        value = {};
      }

      if (this.isTranslated) {
        if (typeof value[name] === 'undefined') {
          value[name] = {};
        }

        value[name][this.language] = new_val;
      } else {
        value[name] = new_val;
      }

      this.$emit('input', value);
    },
  },
};
</script>
