<template>
  <span
    class="icon-wrapper"
    @click.prevent="emitClick"
  >
    <i class="fa fa-times"></i>
  </span>
</template>

<script>
  export default {
    methods: {
      emitClick(event) {
        this.$emit('click', event);
      }
    }
  }
</script>