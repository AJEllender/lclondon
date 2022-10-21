<template>
  <div>
    <div class="has-text-centered notification" v-if="dimensions">
      Dimensions: {{ currentWidth }}px wide by {{ currentHeight }}px high
    </div>
    <iframe
      :style="initialStyle"
      class="preview-frame" 
      :src="source"
      ref="frame">
    </iframe>
  </div>
</template>

<script>
  export default {
    props: {
      source: {
        required: true,
        type: String,
      },

      dimensions: {
        type: Boolean,
        default: true,
      },

      width: {
        type: String,
        default: '100%',
      },

      height: {
        type: String,
        default: '650px',
      },
    },

    data() {
      return {
        bounds: {},
        initialWidth: '100%',
        initialHeight: '650px',
      }
    },

    created() {
      this.initialWidth = this.width;
      this.initialHeight = this.height;
    },

    mounted() {
      this.bounds = this.$refs.frame.getBoundingClientRect();

      if (this.dimensions) {
        let content_window = this.$refs.frame.contentWindow;
        content_window.addEventListener('resize', this.iframeResized);
      }
    },

    computed: {
      currentWidth() {
        return this.bounds.width;
      },

      currentHeight() {
        return this.bounds.height;
      },

      initialStyle() {
        return 'height:' + this.initialHeight + ';width:' + this.initialWidth + ';';
      },
    },

    methods: {
      iframeResized(event) {
        this.$set(this, 'bounds', this.$refs.frame.getBoundingClientRect());
      },
    }
  }
</script>

<style lang="scss" scoped>
  .preview-frame {
    display: block;
    max-width: 100%;
    margin: 0 auto;
    resize: both;
    box-shadow: 0 2px 4px rgba(0,0,0,.15);
  }
</style>
