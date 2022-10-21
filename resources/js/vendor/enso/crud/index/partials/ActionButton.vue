<template>
  <p class="control">
    <button :class="wrapperClass" :title="title" @click.prevent="confirmAction">
      <span v-if="buttonType === 'fa'" class="icon">
        <i :class="buttonContent"></i>
      </span>
      <template v-if="buttonType === 'text'">{{ buttonContent }}</template>
    </button>
  </p>
</template>

<script>
import swal from 'sweetalert2/dist/sweetalert2.js';
import get from 'lodash/get';

export default {
  props: {
    item: {
      required: true,
      type: Object,
    },

    method: {
      required: true,
      type: String,
    },

    route: {
      required: true,
      type: String,
    },

    postData: {
      required: false,
    },

    confirm: {
      type: Object,
    },

    title: {
      required: true,
      type: String,
    },

    wrapperClass: {
      type: String,
      default: 'button',
    },

    button: {
      required: true,
      type: Object,
    },

    onResponse: {
      required: true,
      type: Function,
    },

    onError: {
      required: true,
      type: Function,
    },
  },

  computed: {
    buttonType() {
      return get(this.button, 'type', 'fa');
    },

    buttonContent() {
      return get(this.button, 'content', 'fa fa-pencil-square-o');
    },

    buttonUrl() {
      return this.route.replace('%ID%', this.item.id);
    },

    needsConfirmation() {
      return this.confirm !== undefined;
    },

    confirmTitle() {
      return get(this.confirm, 'title', 'Sure?');
    },

    confirmText() {
      return get(this.confirm, 'text', 'Are you sure?');
    },

    confirmType() {
      return get(this.confirm, 'type', 'warning');
    },
  },

  methods: {
    confirmAction() {
      if (this.needsConfirmation) {
        swal({
          title: this.confirmTitle,
          text: this.confirmText,
          type: this.confirmType,
          showCancelButton: true,
        }).then((result) => {
          if (result.value) {
            this.doAction();
          }
        });

        return;
      }

      this.doAction();
    },

    doAction() {
      let request = {
        method: this.method,
        url: this.buttonUrl,
      };

      if (this.postData !== undefined) {
        request.data = this.postData;
      }

      this.$root.axios
        .request(request)
        .then(this.onResponse)
        .catch(this.onError);
    },
  },
};
</script>
