<template>
  <div class="enso-filter-set columns" style="justify-content:flex-end; flex-wrap:wrap;">
    <div
      v-for="(filterItem, index) in filterItems"
      :key="index"
      class="column"
      :class="getFilterClass(filterItem)"
    >
      <component
        :is="getFilterComponent(filterItem)"
        :label="getFilterLabel(filterItem)"
        :inputValue="getFilterValue(index)"
        v-bind="getFilterProps(filterItem)"
        @input="updateFilters($event, index)"
      ></component>
    </div>
  </div>
</template>

<script>
import Vue from 'vue';
import EnsoFieldSelect from '../components/fields/EnsoFieldSelect.vue';
import EnsoFieldText from '../components/fields/EnsoFieldText.vue';
import EnsoFieldCheckbox from '../components/fields/EnsoFieldCheckbox.vue';
import get from 'lodash/get';
import size from 'lodash/size';
import debounce from 'lodash/debounce';

export default {
  components: {
    EnsoFieldCheckbox,
    EnsoFieldSelect,
    EnsoFieldText,
  },

  props: {
    filterItems: {
      required: true,
      type: Object,
    },

    currentFilters: {
      type: Object,
      default() {
        return {};
      },
    },
  },

  methods: {
    getFilterComponent(filterItem) {
      let type = get(filterItem, 'type', 'text');

      switch (type) {
        case 'date':
          return 'enso-field-date';
        case 'select':
          return 'enso-field-select';
        case 'checkbox':
          return 'enso-field-checkbox';
        case 'text':
        default:
          return 'enso-field-text';
      }
    },

    getFilterValue(filter_name) {
      return get(this.currentFilters, filter_name, null);
    },

    getFilterLabel(filterItem) {
      return get(filterItem, 'label', null);
    },

    getFilterProps(filterItem) {
      return get(filterItem, 'props', {});
    },

    getFilterClass(filterItem) {
      return get(filterItem, 'classes', 'is-4');
    },

    updateFilters(new_value, filter_name) {
      let new_filters = Object.assign({}, this.currentFilters);

      if (size(new_value) > 0) {
        new_filters[filter_name] = new_value;
      } else {
        new_filters[filter_name] = null;
      }

      this.notifyParent(new_filters);
    },

    notifyParent: debounce(function(new_filters) {
      this.$emit('update', new_filters);
    }, 250),
  },
};
</script>
