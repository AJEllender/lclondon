import clone from 'lodash/clone';
import get from 'lodash/get';

export default {
  model: {
    prop: 'inputValue',
  },

  props: {
    id: String,
    item: Object,
    name: String,
    inputValue: {
      required: true,
    },
    // This is the top-level section, i.e. the tab. This is true even if
    // this is a field inside a flexible content section.
    section: Object,
    readonly: Boolean,
    disabled: Boolean,
    label: String,
    placeholder: String,
    helpText: String,
    classes: Array,
    translatable: Boolean,
    language: String,
    flexComponent: {
      type: Boolean,
      default: false,
    },
    fieldsetClasses: {
      type: Array,
      default: function() {
        return [];
      },
    },
    fieldClasses: {
      type: Array,
      default: function() {
        return [];
      },
    },
    errors: {
      type: Array,
      default() {
        return [];
      },
    },
    pattern: {
      type: String,
      default: null,
    },
    validation: {
      type: [Object, Array],
      default: () => {
        return {
          required: null,
          min: null,
          max: null,
        };
      },
    },
  },

  data() {
    return {
      input_classes: ['input'],
      base_control_classes: ['control'],
      wrapper_classes: ['field'],
      default: '',
      translatables: get(enso, 'crud.translatables', []),
    };
  },

  computed: {
    labelClasses() {
      return 'label';
    },

    allInputClasses() {
      var base = '';

      if (this.hasErrors) {
        base = base + 'is-danger ';
      }

      return base + this.input_classes.join(' ');
    },

    allFieldsetClasses() {
      return this.fieldsetClasses.concat(this.wrapper_classes).join(' ');
    },

    controlClasses() {
      return this.fieldClasses.concat(this.base_control_classes).join(' ');
    },

    helpTextClasses() {
      if (this.hasErrors) {
        return 'help is-danger';
      } else {
        return 'help';
      }
    },

    hasErrors() {
      return this.errors.length > 0;
    },

    required() {
      return typeof this.validation.required !== 'undefined' && this.validation.required;
    },

    inputMin() {
      return this.hasMin && this.validation.min;
    },

    inputMax() {
      return this.hasMax && this.validation.max;
    },

    hasMin() {
      return typeof this.validation.min !== 'undefined';
    },

    hasMax() {
      return typeof this.validation.max !== 'undefined';
    },

    underMin() {
      return this.hasMin && this.value.length < this.inputMin;
    },

    overMax() {
      return this.hasMax && this.value.length > this.inputMax;
    },

    lengthClasses() {
      return ['help', 'has-text-right', this.underMin || this.overMax ? 'is-danger' : ''];
    },

    /**
     * Calculate the ID attribute. Allows for overriding
     * by using a prop, e.g. for felxible content subfields,
     * otherwise defaults to a sensible value.
     *
     * @returns String
     */
    id_attr() {
      if (this.id) {
        return this.id;
      } else {
        return 'crud-field-' + this.field_name;
      }
    },

    /**
     * Check whether this field is translatable
     */
    isTranslated() {
      // Flexible content sub-fields are not translated - the whole flex
      // field is translated instead.
      if (this.flexComponent) {
        return false;
      }

      return this.translatable;
    },

    /**
     * Get the value for this field for the current language
     */
    value: {
      get: function() {
        if (this.isTranslated) {
          if (typeof this.inputValue[this.language] !== 'undefined') {
            return this.inputValue[this.language];
          } else {
            return this.default;
          }
        } else {
          return this.inputValue;
        }
      },
      set(newVal) {
        this.updateValue(newVal);
      },
    },
  },

  methods: {
    updateValue(value) {
      if (this.isTranslated) {
        let val = clone(this.inputValue);
        val[this.language] = value;
        this.$emit('input', val);
      } else {
        this.$emit('input', value);
      }
    },

    focus() {
      if (this.$refs.focusElement !== undefined) {
        this.$refs.focusElement.focus();
      }
    },
  },
};
