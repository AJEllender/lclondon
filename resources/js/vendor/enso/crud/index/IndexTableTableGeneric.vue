<template>
  <div class="table-container">
    <table class="table is-fullwidth" :class="tableClasses">
      <thead>
        <th
          v-if="isOrderable && !nested"
          class="is-narrow is-orderable"
          @click="onOrderBy(order_column)"
        >Order</th>
        <th v-if="hasBulkActions" class="is-narrow">
          <toggle-input
            type="checkbox"
            :toggled="bulk_action_toggle"
            @toggle="toggleAllBulkActions"
          ></toggle-input>
        </th>
        <th
          v-for="column in crud.columns"
          @click="onOrderBy(column)"
          :key="column.id"
          :class="thClasses(column)"
        >
          {{ column.title }}
          <span class="icon is-small" v-if="column.orderable_by === orderby">
            <i :class="orderArrowClasses()" aria-hidden="true"></i>
          </span>
        </th>
        <th v-if="hasIndexActions" class="has-text-right is-narrow">Actions</th>
      </thead>
      <draggable element="tbody" :options="dragOptions" :list="items" @end="onDragEnd">
        <template v-for="item in items">
          <index-row
            :key="item.id"
            :item="item"
            :has-bulk-actions="hasBulkActions"
            :has-index-actions="hasIndexActions"
            :index-actions="indexActions(item)"
            :is-draggable="isDraggable"
            :is-orderable="isOrderable"
            :is-toggled="isToggled(item.id)"
            :crud="crud"
            @action-response="onActionResponse"
            @action-error="onActionError"
            @toggle="toggleSingle"
            @change="onRowChange"
          ></index-row>
        </template>
      </draggable>
    </table>
  </div>
</template>

<script>
import clone from 'lodash/clone';
import draggable from 'vuedraggable';
import filter from 'lodash/filter';
import get from 'lodash/get';
import map from 'lodash/map';
import includes from 'lodash/includes';
import indexOf from 'lodash/indexOf';
import IndexRow from './IndexRow.vue';

export default {
  components: {
    draggable,
    IndexRow,
  },

  props: {
    items: {
      type: Array,
      required: true,
    },
    crud: {
      type: Object,
      required: true,
    },
    isOrderable: {
      type: Boolean,
      default: false,
    },
    hasBulkActions: {
      type: Boolean,
      default: false,
    },
    isDraggable: {
      type: Boolean,
      default: false,
    },
    isOrderable: {
      type: Boolean,
      default: false,
    },
    dragOptions: {
      type: Object,
      default: () => {},
    },
    tableClasses: {
      type: Object,
      default: () => {},
    },
    orderby: {
      type: String,
      default: 'id',
    },
    bulkActionToggle: {
      type: Boolean,
      default: false,
    },
    selectedItems: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      bulk_action_toggle: false,
      index_actions: [],
      order_column: enso.crud.nested ? '_lft' : enso.crud.order_column,
      nested: enso.crud.nested,
    };
  },

  computed: {
    hasIndexActions() {
      return this.index_actions.length;
    },
  },

  methods: {
    onDragEnd(e) {
      this.$emit('dragend', e);
    },

    thClasses(column) {
      let classes = {
        'is-orderable': column.orderable_by,
        'has-text-centered': find(['enso-cell-boolean'], function (item) {
          return item === column.component;
        }),
      };

      column.th_classes.forEach((the_class) => {
        classes[the_class] = true;
      });

      return classes;
    },
    onOrderBy(column) {
      this.$emit('orderby', column);
    },
    indexActions(item) {
      return get(item, 'index_actions', []);
    },
    onActionResponse(response) {
      this.$emit('action-response', response);
    },
    onActionError(error) {
      this.$emit('action-error', error);
    },

    onRowChange(item) {
      this.$emit('change', item);
    },

    orderArrowClasses() {
      return 'fa fa-chevron-' + (this.order === 'desc' ? 'down' : 'up');
    },

    toggleAllBulkActions() {
      this.$emit('toggle-all-bulk-actions');
    },

    isToggled(item_id) {
      return includes(this.selectedItems, item_id);
    },

    toggleSingle(new_value, id) {
      let new_selection = clone(this.selectedItems);

      if (new_value === true) {
        if (!includes(new_selection, id)) {
          new_selection.push(id);
        }
      } else {
        if (includes(new_selection, id)) {
          let index = indexOf(new_selection, id);
          new_selection.splice(index, 1);
          new_selection = [...new_selection];
        }
      }

      this.$emit('change-selection', new_selection);

      this.checkToggleAll();
    },

    checkToggleAll() {
      this.$nextTick(function () {
        this.bulk_action_toggle = this.selectedItems.length === this.items.length;
      });
    },

    toggleAllBulkActions() {
      this.bulk_action_toggle = !this.bulk_action_toggle;

      if (this.bulk_action_toggle === true) {
        this.$emit(
          'change-selection',
          filter(
            map(this.items, function (item) {
              return get(item, 'id', null);
            }),
            function (item) {
              return item !== null;
            }
          )
        );
      } else {
        this.$emit('change-selection', []);
      }
    },
  },

  mounted() {
    this.index_actions = get(enso.crud, 'index_actions', []);
  },
};
</script>
