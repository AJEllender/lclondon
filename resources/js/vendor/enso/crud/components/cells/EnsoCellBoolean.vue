<template>
  <td :class="tdClasses" class="has-text-centered">
    <span class="cell-boolean" :class="(isTrue ? 'is-truthy' : 'is-falsey')" v-html="booleanWord"></span>
  </td>
</template>

<script>
import BaseCell from '../mixins/base-cell';

export default {
  mixins: [BaseCell],

  props: {
    displayType: {
      type: String,
      required: true,
    },
  },

  computed: {
    booleanWord() {
      switch (this.displayType) {
        case 'boolean':
          return this.value ? 'True' : 'False';
          break;
        case 'yes-no':
          return this.value ? 'Yes' : 'No';
          break;
        case 'check-cross':
        default:
          return this.value ? '&#10004;' : '&#10008;';
      }
    },

    isTrue() {
      return this.value;
    },

    tdClasses() {
      let additional_classes = [];
      if (this.displayType === 'check-cross') {
        additional_classes = this.value ? 'has-text-success' : 'has-text-danger';
      }

      return this.td_classes.concat(additional_classes).join(' ');
    },
  },
};
</script>
