<template>
  <modal :show="show" @close="close">
    <div class="box">
      <media-list
        mode="picker"
        @selected-files="onSelectFiles"
        override-order="desc"
        override-order-by="created_at"
        :override-per-page="12"
      ></media-list>
    </div>
  </modal>
</template>

<script>
import Modal from '../Modal.vue';
import MediaList from './MediaList.vue';

export default {
  components: {
    Modal,
    MediaList,
  },

  props: {
    show: {
      type: Boolean,
      default: false,
    },
  },

  methods: {
    close() {
      this.$emit('close');
    },

    onSelectFiles(files) {
      this.$emit('selected-files', files);
      this.close();
    },
  },
};
</script>
