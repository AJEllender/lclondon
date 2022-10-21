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
        <table class="table is-fullwidth">
          <draggable element="tbody" :options="dragOptions" :list="value">
            <tr v-for="(relation, index) in value" :key="index">
              <td>{{ relation[settings.label] }}</td>
              <template v-if="pivotFields">
                <td v-for="subField in pivotFields" :key="subField.name" class="is-wide">
                  <component
                    class="has-many-sub-field"
                    :key="index"
                    :is="subField.component"
                    :item="item"
                    :inputValue="getPivotValue(item[section.name][name][index], subField.props.name)"
                    :language="language"
                    :id="'subField-'+index"
                    :section="section"
                    @input="updatePivotValue(arguments[0], subField.props.name, index)"
                    v-bind="subField.props"
                  ></component>
                </td>
              </template>
              <td class="is-control-column">
                <i class="fa fa-minus-circle" @click="onRemove(index)"></i>
              </td>
              <td class="is-control-column">
                <i class="fa fa-reorder"></i>
              </td>
            </tr>
          </draggable>
        </table>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </p>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import Multiselect from 'vue-multiselect';
import draggable from 'vuedraggable';
import clone from 'lodash/clone';
import get from 'lodash/get';
import swal from 'sweetalert2/dist/sweetalert2.js';

export default {
  mixins: [BaseInput],

  components: {
    draggable,
    Multiselect,
  },

  props: ['relationship', 'settings', 'pivotFields'],

  data() {
    return {
      available_options: [],
      loading: false,
      wrapper_classes: ['orderable-relationship-field'],
    };
  },

  methods: {
    getPivotValue(data_set, field_name) {
      return get(data_set, 'pivot.' + field_name, null);
    },

    updatePivotValue(value, subFieldName, relationIndex) {
      let val = clone(this.inputValue);

      if (this.isTranslated) {
        val[this.language][relationIndex]['pivot'][subFieldName] = value;
      } else {
        val[relationIndex]['pivot'][subFieldName] = value;
      }

      this.$emit('input', val);
    },

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
      this.available_options = response.data.data;
      this.loading = false;
    },

    onInput(value, id) {
      if (
        typeof value[value.length - 1].pivot === 'undefined' &&
        typeof this.pivotFields !== 'undefined'
      ) {
        value[value.length - 1]['pivot'] = Object.assign(
          {},
          ...Object.values(this.pivotFields).map((field) => {
            return { [field.props.name]: field.default };
          })
        );
      }

      this.updateValue(value);
    },

    onRemove(index) {
      let val = clone(this.value);
      val.splice(index, 1);
      this.updateValue(val);
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

    dragOptions() {
      return {
        disabled: this.disabled,
      };
    },
  },

  created() {
    if (this.settings.options) {
      this.available_options = this.settings.options;
    }
  },
};
</script>

<style lang="css">
/**
 * This should be scoped CSS but I can't make it work AND not apply to the normal
 * non-ordered relationship field. So, you know, @todo
 */

/* This is a cheap and cheerful way to stop the tags from showing in the
       Multiselect element as we'll be handling them in the table instead */
.orderable-relationship-field .multiselect__tag {
  display: none;
}

.orderable-relationship-field .is-control-column {
  width: 40px;
}

.has-many-sub-field {
  padding: 0 !important;
}
</style>
