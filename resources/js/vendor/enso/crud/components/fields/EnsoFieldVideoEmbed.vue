<template>
  <div :class="allFieldsetClasses">
    <label
        :for="id_attr"
        :class="labelClasses"
        v-if="label"
    >
      <slot name="label">{{ label }}</slot>
    </label>
    <p :class="controlClasses">
      <slot>
        <div class="field has-addons">
          <div class="control has-icons-right is-expanded">
            <input
              type="text"
              class="input"
              placeholder="Paste your video URL here..."
              :name="name"
              :id="id_attr"
              :class="allInputClasses"
              :required="required"
              :readonly="readonly"
              :disabled="disabled"
              v-model="video_url"
              @blur="onParseUrl"
              @keypress.enter.prevent="onParseUrl"
            >
            <span class="icon is-small is-right has-text-success" v-if="valid && video_id !== null">
              <i class="fa fa-check"></i>
            </span>
            <span class="icon is-small is-right has-text-warning" v-else-if="checking">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <span class="icon is-small is-right has-text-danger" v-else-if="has_checked">
              <i class="fa fa-exclamation-triangle"></i>
            </span>
          </div>
        </div>
      </slot>
      <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
      <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
      <span class="help is-danger" v-if="!valid">That URL was not recognised. Please try another.</span>
    </p>
  </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';
  import queryString from 'query-string';
  import findItem from 'lodash/find';

  export default {
    mixins: [BaseInput],

    data() {
      return {
        video_url: '',
        valid: true,
        type: '',
        video_id: null,
        checking: false,
        has_checked: false,
      };
    },

    mounted() {
      if (this.value) {
        this.video_id = typeof this.value.id !== 'undefined' ? this.value.id : null;
        this.canonical_url = typeof this.value.canonical_url !== 'undefined' ? this.value.canonical_url : null;
        this.video_url = this.canonical_url;
        this.type = typeof this.value.type !== 'undefined' ? this.value.type : null;
      }

      if (this.video_url) {
        this.onParseUrl();
      }
    },

    methods: {
      onParseUrl() {
        this.type = null;
        this.video_id = null;
        this.valid = false;
        this.checking = true;
        this.canonical_url = null;

        // If we have an empty input, the user is probably trying to remove the
        // video. So we'll reset the input.
        if (this.video_url.length === 0) {
          this.reset();
          return;
        }

        const regexes = [
          // Normal Youtube URLs e.g. youtube.com/v=XXXXXXXXXX
          ['youtube', /(?:http[s]?:)?\/\/(?:www\.)?youtube\.com\/(?:.*)v=([-_a-zA-Z0-9]+)/, 'https://www.youtube.com/watch?v=<ID>'],
          // Youtube path URLs e.g. http://www.youtube.com/v/XXXXXXXXX
          ['youtube', /(?:http[s]?:)?\/\/(?:www\.)?youtube\.com\/v\/([-_a-zA-Z0-9]+)/, 'https://www.youtube.com/watch?v=<ID>'],
          // Youtube short URLs e.g. youtu.be/XXXXXXXX
          ['youtube', /(?:http[s]?:)?\/\/(?:www\.)?youtu\.be\/([-_a-zA-Z0-9]+)/, 'https://www.youtube.com/watch?v=<ID>'],
          // Normal Vimeo URLs e.g. vimeo.com/XXXXXXXX or player embed links e.g. https://player.vimeo.com/video/XXXXXXX
          ['vimeo', /(?:http[s]?:)?\/\/(?:www\.)?(?:player\.)?vimeo\.com(?:)\/(?:video\/)?([0-9]+)/, 'https://www.vimeo.com/<ID>'],
          // Vimeo channel video e.g. vimeo.com/channels/XXXXXXX
          ['vimeo', /(?:http[s]?:)?\/\/(?:www\.)?vimeo\.com(?:)\/(?:channels\/)([0-9]+)/, 'https://vimeo.com/<ID>'],
          // Vimeo group video e.g. vimeo.com/groups/ZZZZZZZ/videos/XXXXXXXXX
          ['vimeo', /(?:http[s]?:)?\/\/(?:www\.)?vimeo\.com(?:)\/(?:groups\/(?:.*)\/videos\/)([0-9]+)/, 'https://vimeo.com/<ID>'],
          // Vimeo album video e.g. vimeo.com/album/ZZZZZZZ/video\XXXXXXXX
          ['vimeo', /(?:http[s]?:)?\/\/(?:www\.)?vimeo\.com(?:)\/(?:album\/(?:[0-9]*)\/video\/)([0-9]+)/, 'https://vimeo.com/<ID>'],
          // Vimeo staff picks video e.g. vimeo.com/channels/{channel-name}\XXXXXXXX
          ['vimeo', /(?:http[s]?:)?\/\/(?:www\.)?vimeo\.com(?:)\/(?:channels\/(?:.*)\/)([0-9]+)/, 'https://vimeo.com/<ID>'],
        ];

        const matched = findItem(regexes, (reg) => {
          let [type, pattern, url_template] = reg;
          let result = this.video_url.match(pattern);

          if (result === null) {
            return false;
          }

          this.type = type;
          this.video_id = result[1];
          this.valid = true;
          this.canonical_url = url_template.replace('<ID>', this.video_id);
        });

        this.has_checked = true;
        this.checking = false;
        this.emitValue();
      },

      reset() {
        this.valid = true;
        this.checking = false;
        this.has_checked = false;
        this.emitValue();
      },

      emitValue() {
        this.$emit('input', {
          type: this.type,
          id: this.video_id,
          canonical_url: this.canonical_url,
          options: [],
        });
      }
    },
  }
</script>
