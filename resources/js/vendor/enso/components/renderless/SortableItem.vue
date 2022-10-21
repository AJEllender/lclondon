<script>
  /**
   * Acts as a wrapper that just injects a passed class to the root
   * element of the Draggable Item. Keeps all instantiation props on the
   * SortableList.
   */
  export default {
    inject: [
      "sortableListItemClass"
    ],

    mounted() {
      this.$el.classList.add(this.sortableListItemClass);
    },

    render() {
      return this.$slots.default[0];
    }
  }
</script>