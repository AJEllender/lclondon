<template>
  <form class="enso-media-file" @submit.prevent="onSubmit">
    <div v-if="file.preview_full" class="enso-media-file__preview is-relative">
      <img
        :src="file.preview_full"
        class="enso-media-file__preview-image is-block"
        alt
        @click="onPickFocalPoint"
      />
      <a
        v-if="hasFocalPoint && file.mediatype === 'image'"
        class="enso-media-file__focal-point is-block"
        :style="focalPointStyle"
        @click.prevent="clearFocalPoint"
        title="Focal point. Click here to remove."
      ></a>
    </div>
    <div v-if="file.mediatype === 'image'" class="is-clearfix">
      <div class="help is-pulled-right">
        <a v-if="hasFocalPoint" @click.prevent="clearFocalPoint">
          Remove focal point
          <i class="fa fa-times"></i>
        </a>
        <span v-else>
          Click the image to add a focal point
          <a @click.prevent="focus_help = !focus_help">
            <i class="fa fa-info-circle"></i>
          </a>
        </span>
      </div>
    </div>
    <div v-if="focus_help" class="message is-info">
      <div class="message-header">
        <p>What is an image focal point?</p>
        <button class="delete" @click.prevent="focus_help = false" aria-label="delete"></button>
      </div>
      <div class="message-body">
        <div class="content">
          <p>When cropping an image, Enso will try to keep the focal point visible and will avoid cropping it out.</p>
          <p>For example, if you have a photo of a person you might add a focal point to the person's face so that it is always visible.</p>
          <p>If a focal point is not set, the image will be cropped to the center.</p>
          <p>To add a focal point, click the image. To remove the focal point, click the focal point again.</p>
        </div>
      </div>
    </div>

    <div
      class="notification is-grey"
    >Please note that any changes made here will affect this file wherever it is used on the site.</div>

    <div class="field">
      <p>
        <strong>Url</strong>
      </p>
      <p>
        <a :href="file.url" target="_blank">{{ file.url }}</a>
      </p>
    </div>
    <div class="field" :class="{'has-addons' : filename_updated}">
      <label for="filenameDetailsTitle" class="label">Filename</label>
      <div class="control is-expanded">
        <input id="filenameDetailsTitle" class="input" v-model="new_filename" type="text" />
      </div>
      <div class="control">
        <button
          v-if="filename_updated"
          type="button"
          class="button is-warning"
          @click="onUpdateFilename"
        >Update</button>
      </div>
    </div>
    <div class="field">
      <label for="filedetailTitle" class="label">Title</label>
      <input id="fileDetailsTitle" class="input" v-model="file.title" type="text" />
    </div>
    <div class="field">
      <label for="filedetailCaption" class="label">Caption</label>
      <input id="fileDetailsCaption" class="input" v-model="file.caption" type="text" />
    </div>
    <div class="field">
      <label for="filedetailAltText" class="label">Alt Text</label>
      <input id="fileDetailsAltText" class="input" v-model="file.alt_text" type="text" />
    </div>
    <table class="table is-fullwidth">
      <tr>
        <th>Filename:</th>
        <td>{{ file.filename }}</td>
      </tr>
      <tr>
        <th>File Size:</th>
        <td>{{ file.filesize }}</td>
      </tr>
      <tr>
        <th>ID:</th>
        <td>{{ file.id }}</td>
      </tr>
      <tr>
        <th>Uploaded by:</th>
        <td>{{ file.user_displayname }}</td>
      </tr>
      <tr>
        <th>Uploaded at:</th>
        <td>{{ created_at }}</td>
      </tr>
      <tr>
        <th>Updated at:</th>
        <td>{{ updated_at }}</td>
      </tr>
      <tr v-if="allowDelete">
        <th>Usage:</th>
        <template v-if="show_file_in_use_message">
          <td
            v-if="file.usage_count > 0"
          >This file is used in {{ file.usage_count }} places and therefore can't be deleted.</td>
          <td v-else>This file is not being used and can be deleted.</td>
        </template>
        <template v-else>
          <td>This file may be in use elsewhere on the site. Check before deleting!</td>
        </template>
      </tr>
    </table>
    <div class="field is-clearfix">
      <div class="is-pulled-right">
        <button
          v-if="allowDelete"
          type="button"
          :disable="file.usage_count > 0"
          @click="onDelete"
          class="button is-danger"
        >Delete</button>
        <button
          type="submit"
          :disable="is_saving"
          class="button is-success"
        >{{ is_saving ? 'Saving...' : 'Save' }}</button>
      </div>
    </div>
  </form>
</template>

<script>
import moment from 'moment';
import swal from 'sweetalert2';

export default {
  props: {
    file: {
      type: Object,
      required: true,
    },
    allowDelete: {
      type: Boolean,
      default: true,
    },
  },

  data() {
    return {
      date_format: 'ddd Do MMM YYYY H:mm:ss',
      is_saving: false,
      show_file_in_use_message: enso.show_file_in_use_message,
      new_filename: this.file.original_filename,
      focus_x: this.file.fileinfo.focus_x,
      focus_y: this.file.fileinfo.focus_y,
      focus_help: false,
    };
  },

  methods: {
    // @todo this is duplicated in MediaBrowser.vue. Once we start using VueX
    // this can go away completely
    onDelete() {
      swal({
        title: 'Are you sure?',
        text: 'You will not be able to undo this!',
        type: 'warning',
        showCancelButton: true,
      }).then(() => {
        this.axios
          .delete('/admin/media/delete/' + this.file.id)
          .then((response) => {
            if (response.data.status === 'fail') {
              swal({
                title: 'Oops',
                text: response.data.message,
                type: 'error',
              });
            }

            this.$emit('deleted');
          })
          .catch((error) => {
            swal({
              title: 'Oops',
              text: error.response.data.message,
              type: 'error',
            });
          });
      });
    },

    onUpdateFilename() {
      this.is_saving = true;

      this.axios
        .patch('/admin/media/' + this.file.id + '/update-filename', {
          new_filename: this.new_filename,
        })
        .then((response) => {
          this.file.filename = response.data.data.filename;
          this.file.original_filename = response.data.data.original_filename;
          this.file.preview = response.data.data.preview;
          this.file.url = response.data.data.url;
        })
        .catch((error) => {
          swal({
            title: 'Oops',
            text: 'There was a problem.',
            type: 'error',
          });
        })
        .finally(() => {
          this.is_saving = false;
        });
    },

    onSubmit() {
      this.is_saving = true;

      this.axios
        .patch('/admin/media/' + this.file.id, {
          main: {
            title: this.file.title,
            caption: this.file.caption,
            alt_text: this.file.alt_text,
            focus_x: this.focus_x,
            focus_y: this.focus_y,
          },
        })
        .then((response) => {
          this.$emit('close');
        })
        .catch((error) => {
          swal({
            title: 'Oops',
            text: response.data.message,
            type: 'error',
          });
        })
        .finally(() => {
          this.is_saving = false;
        });
    },

    onPickFocalPoint(e) {
      const rect = e.target.getBoundingClientRect();
      const height = e.target.height;
      const width = e.target.width;
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;

      this.focus_x = x / width;
      this.focus_y = y / height;

      this.file.fileinfo.focus_x = this.focus_x;
      this.file.fileinfo.focus_y = this.focus_y;
    },

    clearFocalPoint() {
      this.focus_x = null;
      this.focus_y = null;
      this.file.fileinfo.focus_x = null;
      this.file.fileinfo.focus_y = null;
    },
  },

  computed: {
    created_at() {
      const created_at = moment(this.file.created_at.date);
      return created_at.format(this.date_format);
    },

    updated_at() {
      const updated_at = moment(this.file.updated_at.date);
      return updated_at.format(this.date_format);
    },

    filename_updated() {
      return this.file.original_filename !== this.new_filename;
    },

    focalPointStyle() {
      return {
        left: this.focus_x * 100 + '%',
        top: this.focus_y * 100 + '%',
      };
    },

    hasFocalPoint() {
      return this.focus_x && this.focus_y;
    },
  },
};
</script>
