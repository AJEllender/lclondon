<template>
  <div class="enso-file">
    <div class="enso-file__preview">
      <img :src="preview" @click="onClick" />
      <button type="button" class="delete enso-file__delete" @click="onDelete"></button>
      <button type="button" class="enso-file__settings" @click="onClick">
        <i class="fa fa-cog"></i>
      </button>
    </div>
    <div v-if="filename" class="enso-file__name">
      {{ filename }}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    id: {
      type: Number,
      required: true,
    },
    preview: {
      type: String,
      required: true,
    },
    filename: {
      type: String,
      default: null,
    },
  },

  methods: {
    onDelete() {
      this.$emit('delete');
    },

    onClick() {
      this.$emit('click');
    },
  },
};
</script>
