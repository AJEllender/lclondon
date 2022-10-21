<template>
  <transition name="modal">
    <div class="modal is-active" @click="close" v-if="show">
      <div class="modal-background" @click="close"></div>
      <div class="modal-content" @click.stop>
        <slot></slot>
      </div>
      <button class="modal-close is-large" aria-label="close" @click="close"></button>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false,
    },
  },

  methods: {
    close: function () {
      this.$emit('close');
    },
  },

  mounted() {
    document.addEventListener('keydown', e => {
      if (this.show && e.key == 'Escape') {
        this.close();
      }
    });
  },
};
</script>
