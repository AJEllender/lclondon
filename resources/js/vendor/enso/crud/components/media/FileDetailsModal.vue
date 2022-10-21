<template>
  <modal :show="show" @close="close" class="file-modal">
    <div class="box">
      <file-details
        :file="file"
        :allow-delete="allowDelete"
        @close="close"
        @deleted="deleted"
      ></file-details>
    </div>
  </modal>
</template>

<script>
import FileDetails from './FileDetails.vue';
import Modal from '../Modal.vue';

export default {
  components: {
    Modal,
    FileDetails,
  },

  props: {
    file: {
      type: Object,
      required: true,
    },

    show: {
      type: Boolean,
      default: false,
    },

    allowDelete: {
      type: Boolean,
      default: true,
    },
  },

  methods: {
    deleted() {
      this.$emit('deleted');
      this.close();
    },

    close() {
      this.$emit('close');
    },
  },
};
</script>
