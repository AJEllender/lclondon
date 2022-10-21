<template>
  <div :class="allFieldsetClasses" v-html="inputValue"></div>
</template>

<script>
import BaseInput from '../mixins/base-input';

export default {
  mixins: [BaseInput],
};
</script>
