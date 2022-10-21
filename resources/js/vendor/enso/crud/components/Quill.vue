<template>
    <div>
        <div class="ui attached segment" ref="quill" @click.prevent="focusEditor"></div>
    </div>
</template>

<script>
import cloneDeep from 'lodash/cloneDeep';
import defaultsDeep from 'lodash/defaultsDeep';
import Quill from 'quill'

export default {
    model: {
        prop: 'content',
    },

    props: {
        content: {},

        formats: {
            type: Array,
            default() {
                return [];
            },
        },

        keyBindings: {
            type: Array,
            default() {
                return [];
            },
        },

        output: {
            default: 'delta',
        },

        bus: {
            default: false,
        },

        config: {
            default() {
                return {};
            },
        },
    },

    data() {
        return {
            editor: {},
            defaultConfig: {
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [
                            { 'list': 'ordered' }, { 'list': 'bullet' }
                        ],
                        [{ 'align': [] }],
                    ],
                },
                theme: 'snow',
            },
        }
    },

    mounted() {
        if (this.keyBindings.length) {
            this.defaultConfig.modules.keyboard = {
                bindings: this.keyBindings.map((binding) => {
                    if (binding.remove) {
                        return false;
                    }

                    return {
                        key: binding.key,
                        metaKey: true,
                        handler: binding.method.bind(this),
                    }
                })
            }
        }

        // @todo this is a quick hack to prevent toolbar config getting merged
        // with the defaults and ending up with a weird hybrid toolbar
        // that is neither one nor the other. There are probably tidier ways
        // of doing this.
        var toolbar_options = null;
        if (typeof this.config.modules.toolbar !== 'undefined') {
            toolbar_options = cloneDeep(this.config.modules.toolbar);
        }
        var options = defaultsDeep(this.config, this.defaultConfig)
        if (toolbar_options) {
            options.modules.toolbar = toolbar_options;
        }

        this.editor = new Quill(this.$refs.quill, options);

        if (this.content && this.content !== '') {
            if (this.output != 'delta') {
                this.editor.pasteHTML(this.content);
            } else {
                this.editor.setContents(this.content);
            }
        }

        this.editor.on('text-change', (delta, source) => {
            this.$emit('text-change', this.editor, delta, source);
            this.$emit('input', this.output != 'delta' ? this.editor.root.innerHTML : this.editor.getContents());
        })

        this.editor.on('selection-change', range => {
            this.$emit('selection-change', this.editor, range);
        })

        if (this.bus) {
            this.bus.$on('focus-editor', this.focusEditor());
            this.bus.$on('set-content', content => this.editor.setContents(content));
            this.bus.$on('set-html', html => {
                if (!html || html === '') return;

                this.editor.root.innerHTML = html;
            });
        }

        this.$on('focus-editor', this.focusEditor);
        this.$on('set-content', content => this.editor.setContents(content));
        this.$on('set-html', html => {
            if (!html || html === '') return;

            this.editor.root.innerHTML = html;
        });
    },

    methods: {
        focusEditor(e) {
            if (e && e.srcElement) {
                let classList = e.srcElement.classList;
                let isSegment = false;

                classList.forEach(className => {
                    if (className === 'segment') {
                        isSegment = true;
                        return;
                    }
                })

                if (!isSegment) return;
            }

            this.editor.focus();
        },

        setContents(json, html) {
            if (json && typeof json.ops !== 'undefined') {
                this.editor.setContents(json.ops);
            } else {
                this.editor.pasteHTML(html);
            }
        }
    },

    beforeDestroy() {
        if (this.bus) {
            this.bus.$off('focus-editor');
            this.bus.$off('set-content');
            this.bus.$off('set-html');
        }
    },
}
</script>