const mix = require('laravel-mix');
const path = require('path');
const webpack = require('webpack');
const ImageminPlugin = require('imagemin-webpack-plugin').default;
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');

mix.webpackConfig({
  resolve: {
    modules: [
      path.resolve(__dirname, 'vendor/yadda/enso-core/resources/js'),
      path.resolve(__dirname, 'node_modules'),
    ],
  },
  plugins: [
    new webpack.NormalModuleReplacementPlugin(/^\.\/package$/, function (result) {
      if (/cheerio/.test(result.context)) {
        result.request = './package.json';
      }
    }),
    new ImageminPlugin({
      pngquant: {
        quality: '95-100',
      },
      test: /\.(jpe?g|png|gif)$/i,
    }),
    new SVGSpritemapPlugin('./resources/svg/sprite/*.svg', {
      output: {
        filename: 'svg/sprite.svg',
        chunk: { keep: true },
        svg: { sizes: false },
        svgo: true,
      },
      sprite: {
        prefix: false,
        generate: {
          title: true,
          symbol: true,
        },
      },
    }),
  ],
});

mix
  .js('resources/js/app.js', 'public/js/app.js')
  .vue()
  .postCss('resources/css/app.css', 'public/css', [require('tailwindcss')])
  .copyDirectory('resources/images', 'public/img')
  .copyDirectory('resources/svg', 'public/svg')
  // .version(['public/svg/sprite.svg'])
  .sourceMaps();
