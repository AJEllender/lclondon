const defaultTheme = require('tailwindcss/defaultTheme');
const plugin = require('tailwindcss/plugin');

module.exports = {
  theme: {
    extend: {
      fontFamily: {
        sans: ['Rubik', ...defaultTheme.fontFamily.sans],
        title: ['Orbitron', ...defaultTheme.fontFamily.sans],
      },
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
    require('@tailwindcss/typography'),
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
