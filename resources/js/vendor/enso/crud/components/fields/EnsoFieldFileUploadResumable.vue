<template>
  <div class="container-fluid uploader" :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </label>

    <draggable
      element="div"
      :list="current_files"
      class="uploader__files"
      v-if="current_files.length > 0"
    >
      <file-preview
        v-for="file in current_files"
        :key="file.id"
        :id="file.id"
        :preview="file.preview"
        @delete="onDelete(file)"
        @click="onClickPreview(file)"
      ></file-preview>
    </draggable>

    <file-uploader
      v-show="canAddFiles"
      ref="uploader"
      :upload-route="uploadRoute"
      :upload-path="uploadPath"
      :max-files="uploadableCount"
      :max-file-size="parseInt(maxFileSize, 10)"
      @file-success="onFileSuccess"
      @complete="onUploadComplete"
    >
      <a
        v-if="showPicker"
        class="button is-success"
        @click.prevent="onShowPicker"
        :disabled="!canAddFiles"
        >Choose</a
      >
    </file-uploader>

    <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
    <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>

    <portal to="modals" v-if="showPickerModal">
      <file-picker-modal
        :show="showPickerModal"
        @close="showPickerModal = false"
        @selected-files="onSelectedFiles"
      ></file-picker-modal>
    </portal>

    <portal to="modals" v-if="fileForModal !== null">
      <file-details-modal
        :show="true"
        :file="fileForModal"
        :allow-delete="false"
        @close="fileForModal = null"
        @deleted="onDelete(fileForModal)"
      ></file-details-modal>
    </portal>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import filter from 'lodash/filter';
import FilePreview from '../media/FilePreview';
import FilePickerModal from '../media/FilePickerModal.vue';
import FileUploader from './FileUploader.vue';
import FileDetailsModal from '../media/FileDetailsModal.vue';
import some from 'lodash/some';
import draggable from 'vuedraggable';

export default {
  mixins: [BaseInput],

  components: {
    draggable,
    FilePreview,
    FilePickerModal,
    FileUploader,
    FileDetailsModal,
  },

  data() {
    return {
      // Uploaded files - this is what will be saved to/loaded from the server
      current_files: [],
      showPickerModal: false,
      fileForModal: null,
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
    relationship: {
      type: String,
      required: true,
    },
    showPicker: {
      type: Boolean,
      default: true,
    },
    // In MB
    maxFileSize: {
      default: 5,
    },
  },

  mounted() {
    if (this.value) {
      this.current_files = this.value;
    }
  },

  methods: {
    onClickPreview(file) {
      this.fileForModal = file;
    },

    onFileSuccess(file) {
      this.current_files.push(file);
      this.updateValue(this.current_files);
    },

    onUploadComplete() {
      this.updateValue(this.current_files);
      this.$emit('upload-complete');
    },

    removeAllFiles() {
      this.current_files = [];
      this.$refs.uploader.removeAllFiles();
      this.updateValue(this.current_files);
    },

    onDelete(file) {
      this.current_files = filter(this.current_files, (item) => {
        return item.filename !== file.filename;
      });

      this.updateValue(this.current_files);
    },

    onShowPicker() {
      if (!this.canAddFiles) {
        return;
      }

      this.showPickerModal = true;
    },

    onHidePicker() {
      this.showPickerModal = false;
    },

    onSelectedFiles(files) {
      files.forEach((file) => {
        this.addFile(file);
      });

      this.updateValue(this.current_files);
    },

    addFile(file) {
      if (
        !some(this.current_files, (loop_file) => {
          return loop_file.id === file.id;
        })
      ) {
        this.current_files.push(file);
      }
    },
  },

  computed: {
    /**
     * Calculate the ID attribute. Allows for overriding
     * by using a prop, e.g. for felxible content subfields,
     * otherwise defaults to a sensible value.
     *
     * @returns String
     */
    id_attr() {
      if (this.id) {
        return this.relationship + '-' + this.id;
      } else {
        return 'crud-field-' + this.relationship + '-' + this.field_name;
      }
    },

    canAddFiles() {
      return this.maxFiles < 1 || this.current_files.length < this.maxFiles;
    },

    // Number of files that can still be uploaded. -1 = unlimited
    uploadableCount() {
      if (this.maxFiles < 1) {
        return -1;
      }

      return this.maxFiles - this.current_files.length;
    },
  },
};
</script>
