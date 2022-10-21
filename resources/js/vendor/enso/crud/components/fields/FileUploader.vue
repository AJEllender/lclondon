<template>
  <div>
    <div
      class="uploader__drop-target"
      :class="{'uploader__drop-target--droppable': dragged_over}"
      ref="drop"
      @dragover="dragged_over = true"
      @dragleave="dragged_over = false"
      @drop="dragged_over = false"
    >
      <div class="buttons">
        <span href="#" class="button is-success" ref="browse" :disabled="!canAddFiles">Upload</span>
        <slot></slot>
      </div>
    </div>
    <table
      class="table is-striped is-hoverable is-fullwidth uploader__staged-files"
      v-if="staged_files.length > 0"
    >
      <thead>
        <th>Filename</th>
        <th>Size</th>
        <th class="has-text-right">Actions</th>
      </thead>
      <tr v-for="file in staged_files" :key="file.fileName">
        <td>{{ file.fileName }}</td>
        <td>{{ bytesToMB(file.size) }}MB</td>
        <td class="has-text-right">
          <button
            type="button"
            class="button is-danger"
            @click.prevent="unstageFile(file)"
            :disabled="uploading"
          >
            <span class="icon">
              <i class="fa fa-times"></i>
            </span>
          </button>
        </td>
      </tr>
    </table>
    <button
      v-if="staged_files.length > 0 && !this.uploading"
      type="button"
      class="button is-primary"
      @click="onUpload"
    >{{ uploading ? 'Uploading...' : 'Upload' }}</button>
    <progress
      v-if="uploading"
      class="progress is-info"
      max="1"
      :value="progress"
    >{{ progressPercent }}%</progress>
    <span :class="helpTextClasses" v-if="upload_error">{{ upload_error }}</span>
    <span :class="helpTextClasses" v-if="error">{{ error }}</span>
  </div>
</template>

<script>
import Resumable from 'resumablejs';
import swal from 'sweetalert2/dist/sweetalert2.js';

export default {
  data() {
    return {
      // Files being staged for upload
      staged_files: [],
      error: null,
      dragged_over: false,
      upload_error: null,
      upload_type: 'file', // @todo
      uploading: false,
      progress: 0,
      simultaneousUploads: 3,
    };
  },

  props: {
    uploadRoute: {
      type: String,
      default: '/admin/media/upload-resumable',
    },
    uploadPath: {
      type: String,
      default: 'general',
    },
    maxFiles: {
      type: Number,
      required: true,
    },
    // in MB
    maxFileSize: {
      type: Number,
      default: 5,
    },
  },

  mounted() {
    this.r = new Resumable({
      target: this.uploadRoute,
      maxFiles: this.maxFiles < 1 ? undefined : this.maxFiles,
      maxFileSize: this.maxFileSize * 1024 * 1024,
      maxFileSizeErrorCallback: this.maxFileSizeErrorCallback.bind(this),
      simultaneousUploads: this.simultaneousUploads,
      chunkSize: 0.5 * 1024 * 1024,
      forceChunkSize: true,
      query: {
        path: this.uploadPath,
        upload_type: this.upload_type,
      },
      headers: {
        'X-CSRF-TOKEN': this.getCsrf(),
      },
    });

    this.r.assignBrowse(this.$refs.browse);
    this.r.assignDrop(this.$refs.drop);

    this.r.on('fileSuccess', (file, message) => {
      const response = JSON.parse(message);

      this.$emit('file-success', response.data);
    });

    this.r.on('fileError', (file, message) => {
      this.upload_error = 'Error uploading file: ' + file.fileName;
    });

    this.r.on('complete', () => {
      this.uploading = false;

      this.r.cancel(); // Reset resumable.js

      this.$emit('complete');
    });

    this.r.on('error', (message, file) => {
      this.uploading = false;
      this.error = 'Error: '.message;
    });

    this.r.on('progress', () => {
      this.progress = this.r.progress();
    });

    this.r.on('fileAdded', () => {
      if (this.maxFiles === 1) {
        this.onUpload();
      }
    });

    this.staged_files = this.r.files;
  },

  methods: {
    onUpload() {
      this.error = null;
      this.upload_error = null;
      this.uploading = true;
      this.r.upload();
    },

    getCsrf() {
      var metas = document.getElementsByTagName('meta');

      for (var i = 0; i < metas.length; i++) {
        if (metas[i].getAttribute('name') == 'csrf-token') {
          return metas[i].getAttribute('content');
        }
      }

      return '';
    },

    unstageFile(file) {
      this.r.removeFile(file);
    },

    removeAllFiles() {
      this.r.cancel();
    },

    bytesToMB(bytes) {
      return (bytes / 1000000).toFixed(2);
    },

    maxFileSizeErrorCallback(file, errorCount) {
      swal({
        title: 'Too big!',
        text:
          file.fileName ||
          file.name + ' is too large. Please upload files less than ' + this.maxFileSize + 'MB.',
        type: 'error',
      });
    },
  },

  computed: {
    progressPercent() {
      return this.progress.toFixed(2);
    },

    canAddFiles() {
      if (this.maxFiles === -1) {
        return true;
      }

      return this.maxFiles > this.staged_files.length;
    },

    helpTextClasses() {
      if (this.error !== null) {
        return 'help is-danger';
      }
    },
  },

  watch: {
    maxFiles(max) {
      if (max === -1) {
        max = undefined;
      }

      this.r.opts.maxFiles = max;
    },
  },
};
</script>
