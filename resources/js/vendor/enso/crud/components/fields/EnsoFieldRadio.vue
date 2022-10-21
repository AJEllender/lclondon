<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses">
      <slot name="label">{{ label }}</slot>
    </label>
    <p :class="controlClasses">
      <slot>
        <p class="control" v-for="(option, index) in options" :key="index">
          <label class="radio">
            <input
              type="radio"
              :name="name"
              :value="index"
              :required="required"
              :readonly="readonly"
              :disabled="disabled"
              :checked="!!(value === index)"
              @change="updateValue($event.target.value)"
            />
            {{ option }}
          </label>
        </p>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </p>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';

export default {
  mixins: [BaseInput],

  props: {
    options: {
      default() {
        return {};
      },
    },
  },
};
</script>
