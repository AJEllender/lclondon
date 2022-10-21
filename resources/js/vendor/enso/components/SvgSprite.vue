<template>
  <svg>
    <use :xlink:href="`${path}#${href}`"></use>
  </svg>
</template>

<script>
import * as axios from 'axios';

let isIE11 = !!window.MSInputMethodContext && !!document.documentMode;

export default {
  name: 'svg-sprite',
  props: {
    href: {
      type: String,
      required: true,
    },
    url: {
      type: String,
      default: '/svg/sprite.svg',
    },
  },
  created() {
    let id = 'sprite-' + this.url.replace(/[^a-zA-Z0-9]+/g, '');
    let exists = document.getElementById(id);

    // If we're on IE and haven't loaded the sprite yet, then load the sprite
    if (isIE11 && !exists) {
      axios.get(this.url)
        .then(response => {
          let div = document.createElement('div');
          div.id = id;
          div.innerHTML = response.data;
          div.style.display = 'none';
          document.body.insertBefore(div, document.body.childNodes[0]);
          document.body.classList.add('ie');
        });
    }
  },
  computed: {
    path() {
      return !isIE11 ? this.url: '';
    },
  },
}
</script>
