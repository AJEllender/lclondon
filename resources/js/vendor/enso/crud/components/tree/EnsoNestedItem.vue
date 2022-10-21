<template>
  <draggable
    class="crud-nested-container"
    :class="classes"
    tag="div"
    :list="items"
    :group="{ name: 'g1' }"
    :options="{
      disabled: this.saving,
    }"
    ghost-class="crud-nested-container--ghost"
    @end="onDragEnd"
    @start="onDragStart"
    @change="onChange"
  >
    <div
      v-for="item in items"
      :key="item.id"
      class="crud-nested-group"
      :class="{
        'crud-nested-group--dragging': dragging,
      }"
    >
      <div
        class="crud-nested-item"
        :class="'crud-nested-container--depth-' + (depth % max_depth_colors)"
      >
        <svg
          version="1.1"
          id="Layer_1"
          xmlns="http://www.w3.org/2000/svg"
          x="0"
          y="0"
          viewBox="0 0 30 40"
          xml:space="preserve"
          class="crud-nested-drag-icon"
        >
          <path d="M10 0H0v40h10c11 0 20-9 20-20S21 0 10 0z" fill="#e5e6de" />
          <circle fill="#312b56" cx="13" cy="16" r="1" />
          <circle fill="#312b56" cx="17" cy="16" r="1" />
          <circle fill="#312b56" cx="13" cy="20" r="1" />
          <circle fill="#312b56" cx="17" cy="20" r="1" />
          <circle fill="#312b56" cx="13" cy="24" r="1" />
          <circle fill="#312b56" cx="17" cy="24" r="1" />
        </svg>
        <span>{{ item.label }}</span>
      </div>
      <enso-nested-item
        v-if="showChildren"
        class="crud-nested-item-sub"
        :parent-item="item"
        :items="item.children"
        :depth="depth + 1"
      />
    </div>
  </draggable>
</template>

<script>
import draggable from 'vuedraggable';
import swal from 'sweetalert2/dist/sweetalert2.js';

export default {
  components: {
    draggable,
  },

  props: {
    items: {
      required: true,
      type: Array,
    },
    depth: {
      type: Number,
      default: 0,
    },
    parentItem: {
      type: Object,
    },
    maxDepth: {
      type: Number,
      default: -1,
    },
  },

  data() {
    return {
      dragging: false,
      saving: false,
      crud: enso.crud,
      max_depth_colors: 5, // Any further down and the colours will be reused
    };
  },

  computed: {
    classes() {
      return {
        'crud-nested-container__top': this.depth === 0,
        'crud-nested-container--saving': this.saving,
      };
    },

    showChildren() {
      return this.maxDepth === -1 || this.maxDepth > this.depth;
    },
  },

  mounted() {
    this.endpoint = enso.crud.route;
  },

  name: 'enso-nested-item',

  methods: {
    onDragStart() {
      this.dragging = true;
    },

    onDragEnd() {
      this.dragging = false;
    },

    onChange(e) {
      if (typeof e.removed !== 'undefined') {
        return;
      }

      let action;

      if (typeof e.added !== 'undefined') {
        action = e.added;
      }

      if (typeof e.moved !== 'undefined') {
        action = e.moved;
      }

      if (this.items.length === 1) {
        if (this.parentItem) {
          this.doUpdate(action.element.id, {
            child_of: this.parentItem.id,
          });
        }
      } else if (action.newIndex === 0) {
        this.doUpdate(action.element.id, {
          insert_before: this.items[1].id,
        });
      } else {
        this.doUpdate(action.element.id, {
          insert_after: this.items[action.newIndex - 1].id,
        });
      }
    },

    doUpdate(item_id, options) {
      this.saving = true;

      this.axios
        .post(`${this.endpoint}/${item_id}/rearrange`, {
          nesting: options,
        })
        .catch((error) => {
          swal({
            title: 'Oops...',
            text: 'There was a problem saving your change.',
            type: 'error',
          }).then(() => {
            window.location.reload();
          });
        })
        .then(() => {
          this.saving = false;
        });
    },
  },
};
</script>
