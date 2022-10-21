export default {
  props: {
    value: '',
    item: null,
  },

  data() {
    return {
      td_classes: [],
    };
  },

  computed: {
    tdClasses() {
      return this.td_classes.join(' ');
    },
  },
};
