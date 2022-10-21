<template>
  <p class="control">
    <a :href="buttonUrl" :class="wrapperClass" :title="title">
      <span v-if="buttonType === 'fa'" class="icon"><i :class="buttonContent"></i></span>
      <template v-if="buttonType === 'text'">{{ buttonContent }}</template>
    </a>
  </p>
</template>

<script>
  import get from 'lodash/get';

  export default {
    props: {
      item: {
        require: true,
        type: Object
      },

      route: {
        required: true,
        type: String,
      },

      title: {
        required: true,
        type: String,
      },

      wrapperClass: {
        type: String,
        default: 'button'
      },

      button: {
        required: true,
        type: Object,
      }
    },

    computed: {
      buttonType() {
        return get(this.button, 'type', 'fa');
      },

      buttonContent() {
        return get(this.button, 'content', 'fa fa-pencil-square-o')
      },

      buttonUrl() {
        return this.route.replace('%ID%', this.item.id);
      }
    },
  }
</script>
