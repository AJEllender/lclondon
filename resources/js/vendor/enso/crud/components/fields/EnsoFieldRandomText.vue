<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </label>
    <div class="field has-addons">
      <slot name="prepends"></slot>
      <p :class="controlClasses" class="is-expanded">
        <slot>
          <input
            :name="name"
            :value="value"
            :id="id_attr"
            :placeholder="placeholder"
            :class="allInputClasses"
            :required="required"
            :readonly="readonly"
            :disabled="disabled"
            :pattern="pattern"
            :minlength="inputMin"
            :maxlength="inputMax"
            type="text"
            ref="input"
            @input="updateValue($event.target.value)"
          >
        </slot>
        <span v-if="(hasMin || hasMax) && value" :class="lengthClasses">{{ value.length }}</span>
        <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
        <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
      </p>
      <p class="control">
        <button @click.prevent="makeNewValue" class="button is-info">
          <span class="icon">
            <i class="fa fa-refresh"></i>
          </span>
        </button>
      </p>
      <slot name="appends"></slot>
    </div>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';

export default {
  mixins: [BaseInput],

  props: {
    length: {
      type: Number,
      default: 12,
    },
  },

  methods: {
    makeNewValue() {
      this.updateValue(this.generate());
    },

    generate(len) {
      len = len || this.length;
      var arr = new Uint8Array(len / 2);
      window.crypto.getRandomValues(arr);
      return Array.from(arr, this.byteToHex).join('');
    },

    byteToHex(byte) {
      return ('0' + byte.toString(16)).slice(-2);
    },
  },
};
</script>
