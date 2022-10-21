<template>
  <nav class="enso-sidebar">
    <h2>
      Enso nav goes here!
    </h2>
    <p>
      {{ test }}
    </p>
  </nav>
</template>

<script>
export default {
  data: function () {
    return {
      test: 'foobar'
    };
  }
};
</script>

<style lang="scss" scoped>
  .enso-sidebar {
    color: 'red';
  }
</style>
