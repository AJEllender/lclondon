<template>
  <td class="has-text-centered" :class="tdClasses">
    <span class="cell-publish">
      <button
        v-if="isPublished"
        class="button is-light has-text-success"
        title="Published. Click to unpublish."
        :class="{'is-loading': is_saving}"
        @click.prevent="unpublish"
      >&#10004;</button>
      <button
        v-else
        class="button is-light has-text-danger"
        title="Unpublished. Click to publish immediately."
        :class="{'is-loading': is_saving}"
        @click.prevent="publish"
      >&#10008;</button>
    </span>
  </td>
</template>

<script>
import swal from 'sweetalert2/dist/sweetalert2.js';
import BaseCell from '../mixins/base-cell';

export default {
  mixins: [BaseCell],

  props: {
    publishUrl: {
      type: String,
      required: true,
    },
    unpublishUrl: {
      type: String,
      required: true,
    },
    publishedAttribute: {
      type: String,
      default: 'is_published',
    },
    keyName: {
      type: String,
      default: 'id',
    },
  },

  data() {
    return {
      is_saving: false,
    };
  },

  methods: {
    publish() {
      this.is_saving = true;

      this.axios
        .post(this.publishUrl, {
          values: [this.item[this.keyName]],
        })
        .then(this.onSuccess)
        .catch(this.onError)
        .then(() => {
          this.is_saving = false;
        });
    },

    unpublish() {
      this.is_saving = true;

      this.axios
        .post(this.unpublishUrl, {
          values: [this.item[this.keyName]],
        })
        .then(this.onSuccess)
        .catch(this.onError)
        .then(() => {
          this.is_saving = false;
        });
    },

    onSuccess() {
      this.$emit('change', this.item);
    },

    onError(e) {
      swal({
        title: 'Oops',
        text: 'There was a problem. Please try again.',
        type: 'error',
      });
    },
  },

  computed: {
    isPublished() {
      return this.item[this.publishedAttribute];
    },

    tdClasses() {
      return '';
    },
  },
};
</script>
