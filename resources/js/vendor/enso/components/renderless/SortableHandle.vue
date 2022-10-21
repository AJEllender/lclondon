<script>
  /**
   * Acts as a wrapper that just injects a passed class to the root
   * element of the Drag Handle. Keeps all instantiation props on the
   * SortableList.
   */
  export default {
    inject: [
      "sortableListHandleClass"
    ],

    mounted() {
      this.$el.classList.add(this.sortableListHandleClass);
    },

    render() {
      return this.$slots.default[0];
    }
  }
</script>