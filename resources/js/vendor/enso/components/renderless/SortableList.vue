<script>
  /**
   * Renderless Wrapper for a Sortable list, using Shopify's Draggable
   * library. Intended to be user with SortableItem and SortableHandle 
   * for simple templating, and keeping all of the instantiation props
   * attached to the SortableList. You can choose not to use either of
   * these helper Wrappers.
   * 
   * Note: This works best with objects with ids. This can be used for
   * arrays, but each item in the array must be unique (so can be used
   * as a key binding). You cannot use array index.
   */
  import { Sortable } from '@shopify/draggable';
  import get from 'lodash/get';

  export default {
    model: {
      prop: 'items',
      event: 'update'
    },

    /**
     * Passes class names to children components. Used by
     * the SortableItem and SortableHandle components.
     */
    provide() {
      return {
        sortableListItemClass: this.itemClass,
        sortableListHandleClass: this.handleClass
      };
    },

    props: {
      items: {
        required: true,
        type: Array,
      },

      itemClass: {
        default: "sortable-list-item"
      },

      handleClass: {
        default: "sortable-list-handle"
      },
    },

    methods: {
      /**
       * Simulate Moving an item within an array, but slicing it out
       * from it's previous location, and then inserting it into it's
       * new location by index.
       */
      moveItem(oldIndex, newIndex) {
        const itemRemovedArray = [
          ...this.items.slice(0, oldIndex),
          ...this.items.slice(oldIndex + 1, this.items.length)
        ];

        return [
          ...itemRemovedArray.slice(0, newIndex),
          this.items[oldIndex],
          ...itemRemovedArray.slice(newIndex, itemRemovedArray.length)
        ];
      },
    },

    mounted() {
      /**
       * Instantiates the draggable functionality
       */
      new Sortable(this.$el, {
        draggable: `.${this.itemClass}`,
        handle: `.${this.handleClass}`,
        mirror: {
          constrainDimensions: true
        }
      }).on("sortable:stop", ({ oldIndex, newIndex }) => {
        this.$emit("update", this.moveItem(oldIndex, newIndex));
      });
    },

    render: function(createElement) {
      if (get(this.$scopedSlots, 'default', null)) {
        return this.$scopedSlots.default({
          items: this.items,
        });
      } else {
        return createElement('div', 'You must add a slot-scope to the root element of this sortable list.');
      }
    },
  }
</script>
