<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </label>

    <file-uploader
      v-show="canAddFiles"
      ref="uploader"
      :upload-route="uploadRoute"
      :upload-path="uploadPath"
      :max-files="uploadableCount"
      @file-success="onFileSuccess"
      @complete="onUploadComplete"
    >
      <a
        v-if="showPicker"
        class="button is-success"
        @click.prevent="onShowPicker"
        :disabled="!canAddFiles"
      >Choose</a>
    </file-uploader>

    <div class="gallery">
      <draggable
        :options="dragOptions"
        :list="current_files"
        :component-data="{
          class: 'gallery',
        }"
      >
        <div class="gallery__item" v-for="(image, index) in current_files" :key="image.id">
          <img :src="image.preview" class="gallery__image" alt="Uploaded image" />
          <button type="button" class="delete" @click.prevent="removeImage(index)"></button>
          <div class="gallery__title">{{ image.filename }}</div>
        </div>
      </draggable>
    </div>

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
import draggable from 'vuedraggable';
import clone from 'lodash/clone';
import FileDetailsModal from '../media/FileDetailsModal.vue';
import FilePickerModal from '../media/FilePickerModal.vue';
import FilePreview from '../media/FilePreview';
import FileUploader from './FileUploader.vue';

export default {
  mixins: [BaseInput],

  components: {
    draggable,
    FileDetailsModal,
    FilePickerModal,
    FilePreview,
    FileUploader,
  },

  props: {
    maxFiles: Number,
    maxFilesize: Number,
    uploadRoute: String,
    uploadPath: String,
    acceptedFileTypes: {
      type: String,
      default: null,
    },
    showPicker: {
      type: Boolean,
      default: true,
    },
  },

  data() {
    return {
      upload_type: 'image',
      current_files: [],
      destroying: false,
      wrapper_classes: ['gallery-field'],
      showPickerModal: false,
      fileForModal: null,
    };
  },

  created() {
    let files = this.value;

    if (typeof files === 'undefined' || files === null) {
      this.current_files = [];
    } else {
      // Make sure files is an array of items, for both single and many
      // to many relationships
      if (typeof files.id !== 'undefined') {
        files = [files];
      }

      this.current_files = files;
    }
  },

  methods: {
    onFileSuccess(file) {
      this.current_files.push(file);
    },
    onUploadComplete(file) {
      this.updateValue(this.current_files);
      this.$emit('upload-complete');
    },
    onSelectedFiles(files) {
      this.current_files.push(files[0]);
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

    removeImage(index) {
      let val = clone(this.current_files);
      val.splice(index, 1);

      this.current_files = val;
      this.updateValue(this.current_files);
    },
  },

  computed: {
    /**
     * Generate a unique id.
     * This is arbitrary and only used for labels and to
     * differentiate the dropzone from other dropzones.
     */
    id_attr() {
      if (this.id) {
        return this.id;
      } else {
        return 'upload_' + this.name;
      }
    },

    dragOptions() {
      return {
        disabled: this.disabled,
      };
    },

    // Number of files that can still be uploaded. -1 = unlimited
    uploadableCount() {
      if (this.maxFiles === -1) {
        return -1;
      }

      return this.maxFiles - this.current_files.length;
    },

    canAddFiles() {
      return this.uploadableCount > 0;
    }
  },
};
</script>
