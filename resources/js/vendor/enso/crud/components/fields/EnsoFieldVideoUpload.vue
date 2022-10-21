<template>
    <div :class="allFieldsetClasses">
        <label
            :for="id_attr"
            :class="labelClasses"
            v-if="label"
        >
            <slot name="label">{{ label }}</slot>
        </label>
        <dropzone
            :ref="name"
            :id="id_attr"
            :url="uploadRoute"
            :useFontAwesome="true"
            :maxNumberOfFiles="maxFiles"
            :acceptedFileTypes="acceptedFileTypes"
            @vdropzone-sending="uploadSending"
            @vdropzone-success="uploadSucceeded"
            @vdropzone-removed-file="fileRemoved"
        >
        </dropzone>
        <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
        <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
    </div>
</template>

<script>
  import BaseUpload from './EnsoFieldFileUpload.vue';

  export default {
    mixins: [BaseUpload],

    data () {
      return {
        upload_type: 'video',
      }
    },

    methods: {
      addFileToDropzone: function(file) {
        var mock = {
          file_id: file.id,
          name: file.filename,
          size: file.filesize,
          accepted: true,
          url: '/media/videos/' + file.path + '/' + file.filename,
        };

        this.$refs[this.name].dropzone.files.push(mock);
        this.$refs[this.name].dropzone.emit('addedfile', mock);
        this.$refs[this.name].dropzone.emit('complete', mock);
      }
    }
  }
</script>
