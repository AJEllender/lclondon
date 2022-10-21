<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses">
      <slot name="label">{{ label }}</slot>
    </label>
    <slot name="prepends"></slot>
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
          :group-values="settings.group_value"
          :group-label="settings.group_label"
          :group-select="settings.group_select"
          :disabled="settings.disabled"
          @input="onInput"
          @search-change="settings.ajax_url ? asyncFind() : null"
          ref="multiselect"
        ></multiselect>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </p>
    <slot name="appends"></slot>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import Multiselect from 'vue-multiselect';
import swal from 'sweetalert2/dist/sweetalert2.js';

export default {
  mixins: [BaseInput],

  props: ['settings'],

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
        .catch(function (error) {
          swal({
            title: 'Oops...',
            text: 'Something went wrong!',
            type: 'error',
          });
        });
    },

    receiveItems(response) {
      this.available_options = response.data.data;
      this.loading = false;
    },

    onInput(value, id) {
      this.updateValue(value);
    },
  },

  computed: {
    getName() {
      return this.field_name + '[]';
    },

    ids() {
      if (this.value) {
        if (this.settings.multiple) {
          return this.value.map(function (item) {
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
    if (this.settings.options) {
      this.available_options = this.settings.options;
    }
  },
};
</script>
