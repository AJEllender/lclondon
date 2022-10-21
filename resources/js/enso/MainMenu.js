export default {
  data() {
    return {
      menuVisible: false,
    };
  },
  methods: {
    toggleMenu() {
      this.menuVisible = !this.menuVisible;
    },
  },
  render() {
    return this.$scopedSlots.default({
      menuVisible: this.menuVisible,
      toggleMenu: this.toggleMenu,
    });
  },
};
