<template>
  <div :class="allFieldsetClasses">
    <template v-if="component">
      <component :is="component" v-bind="content"></component>
    </template>
    <div v-else>
      {{ content }}
    </div>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';

export default {
  mixins: [BaseInput],
  props: {
    content: {
      default: '',
    },
    component: {
      type: String,
      default: null,
    },
  },
};
</script>
