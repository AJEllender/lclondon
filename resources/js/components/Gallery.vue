<template>
  <div>
    <div class="flex flex-wrap -m-4 justify-center">
      <div
        v-for="(image, index) in images"
        :key="image.id"
        class="grow-0 shrink-0 p-4"
        :class="itemClass"
      >
        <img
          class="w-full h-auto rounded-lg shadow-lg"
          :src="imageUrl(image)"
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
    },
    perRow: {
      type: Number,
      default: 4,
    },
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
  computed: {
    itemClass() {
      switch (this.perRow) {
        case 3:
          return 'basis-full sm:basis-1/2 lg:basis-1/3';
        case 6:
          return 'basis-1/3 sm:basis-1/4 lg:basis-1/6';
        case 4:
        default:
          return 'basis-1/2 sm:basis-1/3 lg:basis-1/4';
      }
    },
  },
  methods: {
    imageUrl(image) {
      return image.urls['gallery_' + this.perRow];
    },
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
