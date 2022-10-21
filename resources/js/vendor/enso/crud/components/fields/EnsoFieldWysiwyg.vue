<template>
    <div :class="allFieldsetClasses">
        <label
            :for="id_attr"
            :class="labelClasses"
        >
            <slot name="label">{{ label }}</slot>
        </label>
        <p :class="controlClasses">
          <div class="wysiwyg-content">
            <slot>
              <quill
                :content="quillContent"
                :config="editorOptions"
                @text-change="onEditorChange"
                ref="quill"
              >
              </quill>
            </slot>
          </div>
          <span :class="helpTextClasses" v-if="helpText">{{ helpText }}</span>
          <span :class="helpTextClasses" v-for="error in errors" :key="error">{{ error }}</span>
        </p>
    </div>
</template>

<script>
  import BaseInput from '../mixins/base-input';

  export default {
    mixins: [BaseInput],

    props: {
      theme: {
        type: String,
      },
      modules: {
        type: Object,
      },
      formats: {
        type: Array,
      },
    },

    data() {
      return {
        default: {
          html: '',
          json: [],
        },
      };
    },

    methods: {
      onEditorChange(editor, change, delta) {
        if (editor.getText().trim().length > 0) {
          this.updateValue({
              html: editor.root.innerHTML,
              json: JSON.stringify(editor.editor.delta),
          });
        } else {
          // If the editor is empty send back an actual empty string rather
          // than some empty <p> tags
          this.updateValue({
            html: '',
            json: '[]',
          });
        }
      },

      setHTMLContent(html) {
        this.$refs.quill.editor.clipboard.dangerouslyPasteHTML(html);
      },
    },

    mounted() {
      if (this.value && this.value.html) {
        let value_json;
        let json_valid = true;

        try {
          value_json = JSON.parse(this.value.json);
        } catch (e) {
          json_valid = false;
        }

        if (!value_json || !json_valid) {
          this.setHTMLContent(this.value.html);
        }
      }
    },

    computed: {
      editorOptions: function () {
        let opts = {
          modules: this.modules,
          formats: this.formats,
          placeholder: this.placeholder,
          readOnly: this.readonly,
          theme: this.theme,
        };

        if (this.formats) {
          opts.formats = this.formats;
        }

        return opts;
      },

      quillContent() {
        if (!this.value) {
          return [];
        }

        var parsed = {};

        // @todo this is not right - we're using strings somewhere and objects elsewhere
        if (typeof this.value.json !== 'object') {
          try {
            parsed = JSON.parse(this.value.json);
          } catch (e) {
            parsed = [];
          }
        } else {
          parsed = this.value.json;
        }

        return parsed ? parsed : [];
      },
    },

    watch: {
      language(newval) {
        if (this.value !== null && this.value.json && this.value.json.length > 0) {
          if (typeof this.value.json === 'string') {
            let parsed = {};

            try {
              parsed = JSON.parse(this.value.json);
            } catch (e) {
              throw 'Failed to parse WYSIWYG field JSON content.';
            }

            if (parsed.length === 0) {
              this.setHTMLContent('');
              return;
            }

            this.$refs.quill.setContents(parsed);
          } else {
            this.$refs.quill.setContents(this.value.json);
          }
        } else {
          this.setHTMLContent('');
        }
      },
    },
  }
</script>
