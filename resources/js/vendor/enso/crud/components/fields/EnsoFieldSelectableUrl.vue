<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses">
      <slot name="label">{{ label }}</slot>
    </label>
    <p :class="controlClasses">
      <slot>
        <div v-show="show_search || !value" class="columns">
          <div class="column is-one-quarter">
            <multiselect
              :options="cruds"
              :allow-empty="false"
              track-by="id"
              label="label"
              placeholder="Select a data type"
              :multiple="false"
              v-model="crudType"
            ></multiselect>
          </div>
          <div class="column">
            <multiselect
              v-show="crudType"
              :allow-empty="false"
              :placeholder="`Find ${crudType.label}`"
              :options="available_options"
              :multiple="false"
              :max="1"
              :label="settings.label"
              :track-by="settings.track_by"
              :searchable="settings.searchable"
              :clear-on-select="settings.clear_on_select"
              :hide-selected="settings.hide_selected"
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
              :limit="1"
              :loading="loading"
              @input="onInput"
              @search-change="asyncFind"
              ref="multiselect"
            ></multiselect>
          </div>
          <div class="column is-narrow" v-if="value">
            <button type="button" class="button" @click="show_search = false">
              <i class="fa fa-times"></i>
            </button>
          </div>
        </div>
        <div v-show="!(show_search || !value)">
          <div class="field has-addons">
            <div class="control is-expanded">
              <input
                type="text"
                v-model="value"
                @input="updateValue($event.target.value)"
                :class="allInputClasses"
              />
            </div>
            <div class="control">
              <button type="button" class="button" @click="show_search = true">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </div>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ this.field.help_text }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </p>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import Multiselect from 'vue-multiselect';
import swal from 'sweetalert2/dist/sweetalert2.js';
import first from 'lodash/first';

export default {
  mixins: [BaseInput],

  props: ['settings', 'cruds'],

  components: {
    Multiselect,
  },

  data() {
    return {
      options: [],
      available_options: [],
      loading: false,
      show_search: false,
      crudType: first(this.cruds),
    };
  },

  methods: {
    asyncFind() {
      if (!this.crudType) {
        return;
      }

      this.loading = true;
      this.axios
        .get(this.crudType.url, {
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

    onInput(value) {
      this.updateValue(value.url);
      this.show_search = false;
    },
  },

  watch: {
    crudType() {
      this.available_options = [];
    },
  },
};
</script>
