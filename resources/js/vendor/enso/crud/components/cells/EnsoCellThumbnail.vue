<template>
  <td
    class="enso-index-thumbnail"
    :class="td_classes"
  >
    <img :src="value" :alt="value" class="enso-index-thumbnail__image">
  </td>
</template>

<script>
import BaseCell from '../mixins/base-cell';

export default {
  mixins: [BaseCell],
};
</script>
