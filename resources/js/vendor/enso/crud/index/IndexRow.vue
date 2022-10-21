<template>
  <tr :class="item.row_classes">
    <td v-if="isOrderable && !nested">
      <span class="icon has-text-grey-lighter drag-handle" v-if="isDraggable">
        <i class="fa fa-th"></i>
      </span>
    </td>
    <td v-if="hasBulkActions">
      <toggle-input type="checkbox" :value="item.id" :toggled="isToggled" @toggle="toggle"></toggle-input>
    </td>
    <template v-for="column in crud.columns">
      <component
        :is="column.component"
        :item="item"
        :data-name="column.name"
        :value="item[column.name]"
        :key="column.id"
        v-bind="column.props"
        @change="onChange"
      ></component>
    </template>
    <td v-if="hasIndexActions" class="index-actions">
      <div class="field has-addons">
        <component
          v-for="(index_action, index) in indexActions"
          :key="index"
          :is="index_action.component"
          :item="item"
          v-bind="index_action"
          :on-response="onActionResponse"
          :on-error="onActionError"
        ></component>
      </div>
    </td>
  </tr>
</template>

<script>
import LinkButton from './partials/LinkButton.vue';
import ActionButton from './partials/ActionButton.vue';

export default {
  components: {
    ActionButton,
    LinkButton,
  },

  props: {
    item: {
      type: Object,
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
    isDraggable: {
      type: Boolean,
      default: false,
    },
    hasBulkActions: {
      type: Boolean,
      default: false,
    },
    hasIndexActions: {
      type: Number,
      default: 0,
    },
    indexActions: {
      type: Array,
      default: [],
    },
    isToggled: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      nested: enso.crud.nested,
    };
  },

  methods: {
    onActionResponse(response) {
      this.$emit('action-response', response);
    },
    onActionError(error) {
      this.$emit('action-error', error);
    },
    onChange(item) {
      this.$emit('change', item);
    },
    toggle(new_value, id) {
      this.$emit('toggle', new_value, id);
    },
  },
};
</script>
