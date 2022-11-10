module.exports = {
  theme: {
    fontFamily: {
      sans: ['Miriam Libre', 'sans-serif'],
    },
    extend: {
      flex: {
        half: '0 0 50%',
        third: '0 0 33.333333%',
        quarter: '0 0 25%',
      },
      minHeight: {
        '1/2': '50vh',
        '2/3': '66.666666vh',
      },
    }
  },
  variants: {},
  corePlugins: {
    aspectRatio: false,
  },
  plugins: [
    require('@tailwindcss/aspect-ratio'),
  ],
  purge: {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    safelist: [
      {
        pattern: /^bg-/,
      },
    ]
  },
};
