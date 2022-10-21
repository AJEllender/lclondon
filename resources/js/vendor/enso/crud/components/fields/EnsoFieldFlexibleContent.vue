<template>
  <div :class="allFieldsetClasses">
    <template v-if="value.length > 0">
      <button
        v-if="allHidden"
        @click.prevent="showAll"
        type="button"
        class="button is-white is-pulled-right"
      >
        <span class="icon is-small">
          <i class="fa fa-expand"></i>
        </span>
      </button>
      <button v-else @click.prevent="hideAll" type="button" class="button is-white is-pulled-right">
        <span class="icon is-small">
          <i class="fa fa-compress"></i>
        </span>
      </button>
    </template>
    <label :for="id_attr" :class="labelClasses">
      <slot name="label">{{ label }}</slot>
    </label>
    <div class="flexible-content">
      <draggable element="div" :options="dragOptions" v-model="value">
        <div
          v-for="(row, row_id) in value"
          class="flexible-content__row"
          :key="row.loop_id"
          :class="isSettingsShown(row_id) ? 'flexible-content__row--settings' : ''"
        >
          <div
            class="flexible-content__actions is-narrow"
            @click="toggleExpandCollapseRow(row.loop_id)"
          >
            <div class="flexible-content__title-block">
              <span class="flexible-content__row-title">
                {{ rowTitle(row.type, row_id) }}
              </span>
            </div>
            <div class="flexible-content__actions-block">
              <button
                type="button"
                class="button is-light"
                :class="isSettingsShown(row_id) ? 'is-active' : ''"
                v-if="hasSettings(rowSpecs[row.type])"
                title="Toggle Settings View"
                @click.stop="toggleSettingsView(row_id)"
              >
                <span class="icon">
                  <i class="fa fa-cog"></i>
                </span>
              </button>
              <button
                type="button"
                class="button is-light"
                v-if="rowSpecs[row.type].allow_duplication"
                title="Duplicate row"
                @click.stop="duplicateRow(row_id)"
              >
                <span class="icon">
                  <i class="fa fa-copy"></i>
                </span>
              </button>
              <button
                type="button"
                class="button is-light flexible-content__drag-handle"
                v-if="orderable && useDragHandle"
                title="Move row"
                @click.stop
              >
                <span class="icon">
                  <i class="fa fa-arrows-v"></i>
                </span>
              </button>
              <button
                type="button"
                class="button is-light"
                @click.stop="removeRow(row_id)"
                title="Delete Row"
                v-if="rowSpecs[row.type].allow_deletion && allowRowDeletion"
              >
                <span class="icon">
                  <i class="fa fa-times-circle"></i>
                </span>
              </button>
            </div>
          </div>
          <div v-show="visible_rows[row.loop_id]" class="flexible-content__fields">
            <div class="columns is-multiline">
              <template
                v-for="(field_spec, field_spec_name) in isSettingsShown(row_id)
                  ? rowSpecs[row.type].settings_fields
                  : rowSpecs[row.type].fields"
              >
                <component
                  :key="field_spec_name"
                  :is="field_spec.component"
                  :item="item"
                  :input-value="getFieldContent(row, field_spec, isSettingsShown(row_id))"
                  :id="
                    [
                      'enso-flexible',
                      section.name,
                      name,
                      row_id,
                      index,
                      depth,
                      field_spec_name,
                    ].join('-')
                  "
                  :fieldsetClasses="
                    field_spec.props.fieldsetClasses.concat(['flexible-content__field'])
                  "
                  :depth="(depth ? depth + '-' : '') + row_id"
                  :language="language"
                  :flex-component="true"
                  :section="section"
                  @input="
                    updateField(
                      arguments[0],
                      row_id,
                      field_spec.props.name,
                      isSettingsShown(row_id)
                    )
                  "
                  v-bind="field_spec.props"
                ></component>
              </template>
            </div>
          </div>
        </div>
      </draggable>
    </div>
    <div v-if="hasAddableRows">
      <div class="dropdown is-up pull-right is-right" :class="{ 'is-active': show_add_row_menu }">
        <div class="dropdown-trigger">
          <button
            type="button"
            class="button outside-click-exclude"
            aria-haspopup="true"
            aria-controls="addRowMenu"
            :disabled="hasMaxRows()"
            @click="toggleAddRowMenu"
          >
            <span class="outside-click-exclude">{{ addRowLabel }}</span>
            <span class="icon is-small outside-click-exclude">
              <i class="fa fa-plus outside-click-exclude" aria-hidden="true"></i>
            </span>
          </button>
        </div>
        <div
          class="dropdown-menu add-row-menu"
          id="addRowMenu"
          role="menu"
          v-outside-click="{
            exclude: ['outside-click-exclude'],
            handler: hideAddRowMenu,
          }"
        >
          <div class="dropdown-content">
            <a
              class="dropdown-item"
              @click.prevent="addRow(row_spec.name)"
              :disabled="!canAddRowSpec(row_spec.name)"
              v-for="row_spec in addableRowSpecs"
              :key="row_spec.name"
              >{{ row_spec.label }}</a
            >
          </div>
        </div>
      </div>
    </div>
    <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
    <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import clone from 'lodash/clone';
import keys from 'lodash/keys';
import find from 'lodash/find';
import first from 'lodash/first';
import forOwn from 'lodash/forOwn';
import filter from 'lodash/filter';
import get from 'lodash/get';
import swal from 'sweetalert2/dist/sweetalert2.js';
import startCase from 'lodash/startCase';
import draggable from 'vuedraggable';
import sortBy from 'lodash/sortBy';
import every from 'lodash/every';

export default {
  mixins: [BaseInput],

  components: {
    draggable,
  },

  data() {
    return {
      maxId: 1,
      shown_settings: {},
      visible_rows: {},
      show_add_row_menu: false,
    };
  },

  created() {
    let data;

    if (this.isTranslated) {
      data = clone(this.inputValue);

      // We get an array from the backend if it is empty because
      // of the way json_encode "works"
      if (Array.isArray(data) && data.length === 0) {
        data = {};
      }

      forOwn(enso.crud.languages, (lang, lang_code) => {
        if (!Array.isArray(data[lang_code])) {
          data[lang_code] = [];
        }

        data[lang_code] = data[lang_code].map((item) => {
          item.loop_id = this.maxId++;
          return item;
        });
      });
    } else {
      data = this.value.map((item) => {
        item.loop_id = this.maxId++;
        return item;
      });
    }

    this.emitValue(data);
  },

  props: {
    rowSpecs: Object,
    allowRowDeletion: Boolean,
    addRowLabel: {
      type: String,
      default: 'Add row',
    },
    orderable: Boolean,
    useDragHandle: Boolean,
    depth: {
      type: String,
      default: '0',
    },
    index: {
      type: Number,
      default: 0,
    },
    maxRows: {
      type: Number,
      default: null,
    },
  },

  methods: {
    updateField(value, row_id, field_name, is_setting) {
      const type = this.value[row_id].type;
      const rowspec = this.rowSpecs[type];
      let field;
      let data_type = is_setting ? 'settings_fields' : 'fields';

      field = rowspec[data_type][field_name];

      if (this.isTranslated) {
        var new_val = clone(this.inputValue);

        if (typeof new_val[this.language][row_id][data_type] === 'undefined') {
          new_val[this.language][row_id][data_type] = [];
        }

        if (!this.fieldValueExists(row_id, field_name, is_setting)) {
          new_val[this.language][row_id][data_type][field_name] = this.makeEmptyField(field);
        }

        new_val[this.language][row_id][data_type][field_name].content = value;
      } else {
        var new_val = clone(this.value);
        if (
          typeof new_val[row_id][data_type] !== 'object' ||
          Array.isArray(new_val[row_id][data_type])
        ) {
          new_val[row_id][data_type] = {};
        }

        if (!this.fieldValueExists(row_id, field_name, is_setting)) {
          new_val[row_id][data_type][field_name] = this.makeEmptyField(field);
        }

        new_val[row_id][data_type][field_name].content = value;
      }

      this.emitValue(new_val);
    },

    fieldValueExists(row_id, field_name, is_setting) {
      let data_type = is_setting ? 'settings_fields' : 'fields';

      if (this.isTranslated) {
        return typeof this.inputValue[this.language][row_id][data_type][field_name] !== 'undefined';
      } else {
        return typeof this.value[row_id][data_type][field_name] !== 'undefined';
      }
    },

    emitValue(value) {
      this.$emit('input', value);
    },

    removeRow(index) {
      swal({
        title: 'Are you sure?',
        text: 'You will not be able to undo this.',
        type: 'warning',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          var new_val;

          if (this.isTranslated) {
            new_val = clone(this.inputValue);
            new_val[this.language].splice(index, 1);
          } else {
            new_val = clone(this.inputValue);
            new_val.splice(index, 1);
          }

          this.emitValue(new_val);
        }
      });
    },

    makeEmptyField(field) {
      let default_value = clone(field.default);

      return {
        class: 'is-half',
        component: field.component,
        content: default_value,
        field: field.field,
      };
    },

    addRow(type) {
      var spec = this.rowSpecs[type];
      var fields = {};
      var settings_fields = {};
      var new_val = clone(this.inputValue);
      var loop_id = this.maxId++;

      this.show_add_row_menu = false;

      forOwn(spec.fields, (field, name) => {
        fields[name] = this.makeEmptyField(field);
      });

      forOwn(spec.settings_fields, (field, name) => {
        settings_fields[name] = this.makeEmptyField(field);
      });

      if (this.isTranslated) {
        if (!Array.isArray(new_val[this.language])) {
          new_val[this.language] = [];
        }

        new_val[this.language].push({
          type: type,
          fields: fields,
          settings_fields: settings_fields,
          loop_id: loop_id,
        });
      } else {
        new_val.push({
          type: type,
          fields: fields,
          settings_fields: settings_fields,
          loop_id: loop_id,
        });
      }

      this.emitValue(new_val);

      this.visible_rows[loop_id] = true;
    },

    duplicateRow(row_id) {
      // For some reason clone() isn't enough here, we also need to
      // clone each field individually to prevent a reference to the
      // old row remaining
      const new_val = clone(this.inputValue);
      let new_row = {};
      let old_val = {};

      if (this.isTranslated) {
        old_val = new_val[this.language][row_id];
      } else {
        old_val = new_val[row_id];
      }

      new_row = {
        type: old_val.type,
        fields: {},
        loop_id: this.maxId++,
      };

      forOwn(old_val.fields, (field, name) => {
        new_row.fields[name] = clone(field);
      });

      if (this.isTranslated) {
        new_val[this.language].splice(row_id + 1, 0, new_row);
      } else {
        new_val.splice(row_id + 1, 0, new_row);
      }

      this.emitValue(new_val);
    },

    startCase(s) {
      return startCase(s);
    },

    rowExcerpt(rowType, row_index) {
      return this.sanitiseExcerpt(
        this.normaliseExcerpt(this.getRowExcerptValue(rowType, row_index))
      );
    },

    getRowExcerptValue(rowType, row_index) {
      let excerpt_field = get(this.rowSpecs, [rowType, 'excerpt_field']);

      if (excerpt_field) {
        return get(this.value, [row_index, 'fields', excerpt_field, 'content']);
      }

      let title = get(this.value, [row_index, 'fields', 'title', 'content']);

      if (title) {
        return title;
      }

      let name = get(this.value, [row_index, 'fields', 'name', 'content']);

      if (name) {
        return name;
      }

      return null;
    },

    normaliseExcerpt(excerpt) {
      // Strings we can just use
      if (typeof excerpt === 'string') {
        return excerpt;
      }

      // WYSIWYG fields we can use the html attribute
      if (typeof get(excerpt, 'html')) {
        return get(excerpt, 'html', null);
      }

      // Anything else, we don't know how to handle yet
      return null;
    },

    sanitiseExcerpt(excerpt) {
      if (!excerpt) {
        return excerpt;
      }

      let doc = new DOMParser().parseFromString(excerpt, 'text/html');
      let output = doc.body.textContent || '';
      let maxLength = 100;

      if (output.length <= maxLength) {
        return output;
      }

      return output.substring(0, maxLength) + '...';
    },

    rowTitle(rowType, row_index) {
      return [
        this.startCase(get(this.rowSpecs, [rowType, 'label'], rowType)),
        this.rowExcerpt(rowType, row_index),
      ]
        .filter((x) => !!x)
        .join(' - ');
    },

    canAddRowSpec(row_spec_name) {
      if (this.hasMaxInstances(row_spec_name)) {
        return false;
      }

      if (this.hasMaxRows()) {
        return false;
      }

      return true;
    },

    hasMaxInstances(row_spec_name) {
      let row_spec = find(this.rowSpecs, (row) => {
        return row.name === row_spec_name;
      });

      if (row_spec.max_instances === null) {
        return false;
      }

      let count = this.value.filter((row) => {
        return row.type === row_spec_name;
      }).length;

      return count >= row_spec.max_instances;
    },

    hasMaxRows() {
      if (this.maxRows === null) {
        return false;
      }

      return this.value.length >= this.maxRows;
    },

    getFieldContent(row, field_spec, is_setting) {
      let content = get();
      let data_type = is_setting ? 'settings_fields' : 'fields';

      if (
        typeof row[data_type] !== 'undefined' &&
        typeof row[data_type][field_spec.props.name] !== 'undefined'
      ) {
        return row[data_type][field_spec.props.name].content;
      } else {
        return field_spec.default;
      }
    },

    toggleSettingsView(row_id) {
      const dupe = clone(this.shown_settings);

      dupe[row_id] = !dupe[row_id];

      this.shown_settings = dupe;
    },

    isSettingsShown(row_id) {
      if (typeof this.shown_settings[row_id] === 'undefined') {
        return false;
      }

      return this.shown_settings[row_id];
    },

    hasSettings(row_spec) {
      if (!row_spec.settings_fields) {
        return false;
      }

      return Object.keys(row_spec.settings_fields).length > 0;
    },

    toggleExpandCollapseRow(loop_id) {
      this.visible_rows[loop_id] = !this.visible_rows[loop_id];
    },

    hideAll() {
      this.visible_rows = Object.fromEntries(
        Object.entries(this.visible_rows).map(([k, v], i) => [k, false])
      );
    },

    showAll() {
      this.visible_rows = Object.fromEntries(
        Object.entries(this.visible_rows).map(([k, v], i) => [k, true])
      );
    },

    isRowHidden(loop_id) {
      if (typeof this.visible_rows[loop_id] === 'undefined') {
        return false;
      }

      return this.visible_rows[loop_id];
    },

    toggleAddRowMenu() {
      if (Object.keys(this.rowSpecs).length === 1) {
        this.addRow(first(Object.keys(this.rowSpecs)));
      } else {
        this.show_add_row_menu = !this.show_add_row_menu;
      }
    },

    hideAddRowMenu() {
      this.show_add_row_menu = false;
    },
  },

  computed: {
    hasAddableRows() {
      return keys(
        filter(this.rowSpecs, function (item) {
          return item.allow_new;
        })
      ).length;
    },

    dragOptions() {
      return {
        disabled: !this.orderable,
        handle: this.useDragHandle ? '.flexible-content__drag-handle' : null,
        forceFallback: true,
      };
    },

    addableRowSpecs() {
      return sortBy(
        filter(this.rowSpecs, (spec) => {
          return spec.allow_new;
        }),
        'label'
      );
    },

    allVisible() {
      return every(this.visible_rows, (visible) => visible);
    },

    allHidden() {
      return every(this.visible_rows, (visible) => !visible);
    },
  },

  watch: {
    inputValue: function (newInputValue, oldInputValue) {
      if (newInputValue.length > 0) {
        this.visible_rows = Object.assign(
          ...newInputValue.map((val) => {
            return {
              [val.loop_id]: this.isRowHidden(val.loop_id),
            };
          })
        );
      }
    },
  },
};
</script>
