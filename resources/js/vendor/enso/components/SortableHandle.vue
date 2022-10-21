<template>
  <renderless-sortable-handle>
    <span class="icon-wrapper">
      <i class="fa fa-bars"></i>
    </span>
  </renderless-sortable-handle>
</template>

<script>
  import RenderlessSortableHandle from './renderless/SortableHandle.vue';

  export default {
    components: {
      RenderlessSortableHandle,
    },
  }
</script>