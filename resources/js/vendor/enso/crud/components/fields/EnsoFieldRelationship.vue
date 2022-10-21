<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses">
      <slot name="label">{{ label }}</slot>
    </label>
    <p :class="controlClasses">
      <slot>
        <multiselect
          :placeholder="settings.placeholder"
          :options="available_options"
          :multiple="settings.multiple"
          :max="settings.max"
          :label="settings.label"
          :track-by="settings.track_by"
          :searchable="settings.searchable"
          :clear-on-select="settings.clear_on_select"
          :hide-selected="settings.hide_selected"
          :allow-empty="settings.allow_empty"
          :reset-after="settings.reset_after"
          :close-on-select="settings.close_on_select"
          :taggable="settings.taggable"
          :tag-placeholder="settings.tag_placeholder"
          :options-limit="settings.options_limit"
          :block-keys="settings.block_keys"
          :internal-search="settings.internal_search"
          :select-label="settings.select_label"
          :selected-label="settings.selected_label"
          :deselect-label="settings.deselect_label"
          :show-labels="settings.show_labels"
          :limit="settings.limit"
          :loading="loading"
          :max-height="settings.max_height"
          :show-pointer="settings.show_pointer"
          :value="value"
          @input="onInput"
          @search-change="settings.ajax_url ? asyncFind() : null"
          ref="multiselect"
        ></multiselect>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </p>
  </div>
</template>

<script>
// @todo remove the hidden input once we start using ajax to save

import BaseSelect from './EnsoFieldSelect.vue';
import Multiselect from 'vue-multiselect';
import swal from 'sweetalert2/dist/sweetalert2.js';

export default {
  mixins: [BaseSelect],

  props: ['relationship', 'settings'],

  components: {
    Multiselect,
  },

  data() {
    return {
      available_options: [],
      loading: false,
    };
  },

  methods: {
    asyncFind(query) {
      this.loading = true;
      this.axios
        .get(this.settings.ajax_url, {
          params: {
            orderby: this.settings.orderby,
            order: this.settings.order,
            search: this.$refs.multiselect.search,
          },
        })
        .then(this.receiveItems)
        .catch(function(error) {
          swal({
            title: 'Oops...',
            text: 'Something went wrong!',
            type: 'error',
          });
        });
    },

    receiveItems(response) {
      this.available_options = response.data;
      this.loading = false;
    },

    onInput(value, id) {
      if (typeof value !== 'array') {
        this.updateValue([value]);
      } else {
        this.updateValue(value);
      }
    },
  },

  computed: {
    getName() {
      return this.field_name + '[]';
    },

    ids() {
      if (this.value) {
        if (this.settings.multiple) {
          return this.value.map(function(item) {
            return item.id;
          });
        } else {
          return [this.value.id];
        }
      } else {
        return [];
      }
    },
  },

  created() {
    var relationship_final_name = this.relationship.replace(/([A-Z])/g, function($1) {
      return '_' + $1.toLowerCase();
    });

    if (this.settings.options) {
      this.available_options = this.settings.options;
    }
  },
};
</script>
