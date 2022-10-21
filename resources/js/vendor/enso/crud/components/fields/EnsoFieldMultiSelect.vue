<template>
    <div :class="allFieldsetClasses">
        <label
            :for="id_attr"
            :class="labelClasses"
        >
            <slot name="label">{{ field.label }}</slot>
        </label>
        <p :class="controlClasses">
          <slot>
              <select
                  :id="id_attr"
                  :name="field_name"
                  :placeholder="placeholder"
                  :class="allInputClasses"
                  v-model="item[field_name]"
                  :required="required"
                  multiple="multiple"
                  :readonly="field.readonly"
                  :disabled="field.disabled">
                  <option v-for="(option, index) in options" :value="index" :key="index">{{ option }}</option>
              </select>
          </slot>
          <span :class="helpTextClasses" v-if="this.field.help_text">{{ this.field.help_text }}</span>
          <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
        </p>
    </div>
</template>

<script>

  import BaseSelect from './EnsoFieldSelect.vue';

  export default {
    mixins: [BaseSelect],

    computed: {
      getName () {
        return this.name + '[]';
      }
    },

    created() {
      if (typeof this.item[this.field_name] === 'undefined') {
        this.item[this.field_name] = [];
      }
    }
  }

</script>
