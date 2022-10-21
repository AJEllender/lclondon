<template>
  <div class="enso-media-browser">
    <media-list ref="list"></media-list>

    <h2 id="upload" class="title is-3">Upload Files</h2>
    <enso-field-file-upload-resumable
      :input-value="null"
      relationship="browser"
      :max-files="100"
      :max-file-size="maxFileSize"
      @upload-complete="onUploadComplete"
      ref="uploader"
      :show-picker="false"
    ></enso-field-file-upload-resumable>
  </div>
</template>

<script>
import MediaList from './MediaList.vue';

export default {
  props: {
    maxFileSize: {
      type: Number,
      default: 15,
    },
  },

  components: {
    MediaList,
  },

  methods: {
    onUploadComplete() {
      this.$refs.list.getFiles();
      this.$refs.uploader.removeAllFiles();
    },
  },
};
</script>
