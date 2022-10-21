<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </label>
    <div :class="controlClasses">
      <slot>
        <div v-if="editing">
          <a v-if="url" :href="url" target="_blank" rel="noopener noreferrer">{{ url }}</a>
          <span v-else>{{ final_value }}</span>
          <button type="button" class="button is-pulled-right" @click="editing = false">Done</button>
          <input
            :id="id_attr"
            :value="value"
            :name="name"
            :placeholder="placeholder"
            :class="allInputClasses"
            :required="required"
            :readonly="readonly"
            :disabled="disabled"
            type="text"
            @input="updateValue($event.target.value)"
          />
        </div>
        <div v-else>
          <button type="button" class="button is-pulled-right" @click="editing = true">Edit</button>
          <a v-if="url" :href="url" target="_blank" rel="noopener noreferrer">{{ url }}</a>
          <span v-else>{{ final_value }}</span>
        </div>
      </slot>
      <div class="notification is-warning" v-if="!createRoute && inputValue !== original_value">
        <p>
          <strong>Warning!</strong> Changing the slug on existing items will stop links from working. Proceed with caution!
        </p>
        <button type="button" class="button" @click="resetValue()">Reset</button>
      </div>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </div>
  </div>
</template>


<script>
import BaseInput from '../mixins/base-input';
import clone from 'lodash/clone';

export default {
  mixins: [BaseInput],

  props: ['source', 'route', 'createRoute', 'sectionName'],

  data() {
    return {
      editing: false,
      input_classes: ['input', 'is-inline-block'],
      original_value: null,
    };
  },

  created() {
    // Set the original value so we can check whether it's
    // changed and warn users that this could break something.
    this.original_value = this.inputValue;
  },

  methods: {
    slugify(input) {
      if (!input) {
        return '';
      }

      return input
        .toString()
        .toLowerCase()
        .replace(/[ \._]/g, '-')
        .replace(/([^a-zA-Z0-9-]+)/g, '')
        .replace(/-{2,}/g, '-')
        .substring(0, 64)
        .trim();
    },

    resetValue() {
      this.updateValue(this.original_value);
      this.editing = false;
    },

    updateValue(value) {
      if (this.isTranslated) {
        let val = clone(this.inputValue);
        val[this.language] = value;

        Object.keys(val).map((key, index) => {
          val[key] = this.slugify(val[key]);
        });
        this.$emit('input', val);
      } else {
        value = this.slugify(value);
        this.$emit('input', value);
      }
    },
  },

  computed: {
    final_value() {
      if (this.value) {
        return this.value;
      } else {
        if (this.isTranslated) {
          return this.slugify(this.item[this.sectionName][this.source][this.language]);
        } else {
          return this.slugify(this.item[this.sectionName][this.source]);
        }
      }
    },

    url() {
      return this.route.replace('%SLUG%', this.final_value);
    },
  },
};
</script>
