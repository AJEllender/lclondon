<template>
  <div>
    <div class="flex flex-wrap -m-4 justify-center">
      <div
        v-for="(image, index) in images"
        :key="image.id"
        class="grow-0 shrink-0 basis-full sm:basis-1/2 lg:basis-1/3 p-4"
      >
        <img
          class="w-full h-auto rounded-lg shadow-lg"
          :src="image.preview"
          @click.prevent="showMultiple(index)"
        >
      </div>
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
