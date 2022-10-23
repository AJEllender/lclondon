<template>
  <div>
    <div class="w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-8">
      <img
        class="w-full h-auto rounded-lg shadow-lg"
        v-for="(image, index) in images"
        :src="image.preview"
        :key="image.id"
        @click.prevent="showMultiple(index)"
      >
    </div>

    <vue-easy-lightbox escDisabled moveDisabled :visible="visible" :imgs="imgs" :index="index" @hide="handleHide">
    </vue-easy-lightbox>
  </div>
</template>

<script>
import VueEasyLightbox from 'vue-easy-lightbox'

export default {
  components: {
    VueEasyLightbox
  },
  props: {
    images: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      imgs: [],
      visible: false,
      index: 0
    }
  },
  mounted() {
    this.imgs = this.images;
  },
  methods: {
    showMultiple(index) {
      this.index = index;
      this.show()
    },
    show() {
      this.visible = true
    },
    handleHide() {
      this.visible = false
    }
  }
}
</script>
