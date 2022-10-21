<template>
  <div :class="section.class">
    <template v-for="(field, index) in section.fields">
      <component
        :key="index"
        :is="field.component"
        :item="item"
        :inputValue="item[section.name][field.props.name]"
        :language="language"
        :id="'field-'+index"
        :section="section"
        @input="updateValue(arguments[0], field.props.name)"
        v-bind="field.props"></component>
    </template>
  </div>
</template>

<script>
  export default {
    props: {
      section: {
        type: Object,
        default: null,
      },
      item: {
        type: Object,
        default: null,
      },
      language: {
        type: String,
        default: 'en',
      },
    },

    methods: {
      updateValue(value, name) {
        /**
         * This is a hacky way to emulate what v-model should do. I.e. update
         * the "value" prop reactively.
         */
        this.item[this.section.name][name] = value;
      },
    },
  }
</script>