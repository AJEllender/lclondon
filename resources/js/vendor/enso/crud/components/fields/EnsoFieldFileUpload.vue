<template>
  <div :class="allFieldsetClasses">
    <label :for="id_attr" :class="labelClasses" v-if="label">
      <slot name="label">{{ label }}</slot>
    </label>
    <dropzone
      :ref="name"
      :id="id_attr"
      :url="uploadRoute"
      :useFontAwesome="true"
      :maxNumberOfFiles="maxFiles"
      :maxFileSizeInMB="maxFileSize"
      :acceptedFileTypes="acceptedFileTypes"
      @vdropzone-sending="uploadSending"
      @vdropzone-success="uploadSucceeded"
      @vdropzone-removed-file="fileRemoved"
    ></dropzone>
    <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
    <span :class="helpTextClasses" v-for="error in errors">{{ error }}</span>
  </div>
</template>

<script>
import BaseInput from '../mixins/base-input';
import Dropzone from 'vue2-dropzone';
import remove from 'lodash/remove';

export default {
  mixins: [BaseInput],

  props: {
    maxFiles: Number,
    maxFileSize: Number,
    uploadRoute: String,
    uploadPath: String,
    acceptedFileTypes: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      upload_type: 'file',
      current_files: [],
      destroying: false,
    };
  },

  components: {
    Dropzone,
  },

  beforeDestroy() {
    this.destroying = true;
  },

  methods: {
    uploadSending: function(file, xhr, formData) {
      var token_elements = document.getElementsByName('_token'),
        token = token_elements[0].value;

      formData.append('_token', token);
      formData.append('upload_type', this.upload_type);
      formData.append('path', this.uploadPath);
    },

    uploadSucceeded: function(file, response) {
      file.file_id = response.data.id;
      this.current_files.push({
        id: file.file_id,
        filename: file.name,
        filesize: file.size,
      });
      this.updateValue(this.current_files);
    },

    fileRemoved: function(file, error, xhr) {
      if (this.destroying) {
        return;
      }

      var file_to_remove = file.file_id;

      remove(this.current_files, {
        id: file_to_remove,
      });

      this.updateValue(this.current_files);
    },

    addFilesToDropzone: function(files) {
      if ('undefined' !== typeof this.old) {
        for (var i = 0; i < this.old.length; i++) {
          var file = this.old[i];
          this.addFileToDropzone(file);
        }
      } else {
        for (var i = 0; i < files.length; i++) {
          var file = files[i];
          this.addFileToDropzone(file);
        }
      }
    },

    addFileToDropzone: function(file) {
      var mock = {
        file_id: file.id,
        name: file.filename,
        size: file.filesize,
        accepted: true,
      };

      this.$refs[this.name].dropzone.files.push(mock);
      this.$refs[this.name].dropzone.emit('addedfile', mock);
      this.$refs[this.name].dropzone.emit('complete', mock);

      this.updateValue(this.current_files);
    },

    removeAllFiles() {
      this.current_files = [];
      this.$refs[this.name].dropzone.removeAllFiles();
    },
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

  mounted() {
    this.addFilesToDropzone(this.current_files);
    this.$refs[this.name].dropzone.options['maxFilesize'] = this.maxFilesize;
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
  },
};
</script>
