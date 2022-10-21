module.exports = {
  theme: {
    fontFamily: {
      sans: ['Miriam Libre', 'sans-serif'],
    },
    flex: {
      half: '1 1 50%',
    },
  },
  variants: {},
  plugins: [],
  purge: {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
  },
};
