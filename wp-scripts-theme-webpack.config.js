/**
 * External Dependencies
 */
const path = require( 'path' );
const CopyWebpackPlugin = require('copy-webpack-plugin');
// const MiniCssExtractPlugin = require('mini-css-extract-plugin');
// const glob  = require('glob');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = (env, argv) => {

  const config = {
      ...defaultConfig,
      entry: {
        ...defaultConfig.entry(),
        "__theme/css/editor": './assets/scss/editor.scss',
        "__theme/css/main": './assets/scss/main.scss',
        "__theme/js/index": './assets/js/index.js',
        "__theme/js/editor": './assets/js/editor.js',
      },
      module: {
        ...defaultConfig.module,
        rules: [
          ...defaultConfig.module.rules,
          // Disable hashing on font and image files. This allows preloading and caching
          {
            test: /\.(woff|woff2|eot|ttf|otf)$/i,
            type: 'asset/resource',
            generator: {
              filename: '__theme/fonts/[name][ext]',
            },
          },
          {
            test: /\.(webp|png|jpe?g|gif)$/,
            type: 'asset/resource',
            generator: {
              filename: '__theme/images/[name][ext]',
            },
          },
        ],
      },
      plugins: [
        ...defaultConfig.plugins,

        new RemoveEmptyScriptsPlugin(),

        new CopyWebpackPlugin({
          patterns: [
            {
              from: '**/*.{jpg,jpeg,png,gif,svg}',
              to: '__theme/images/[path][name][ext]',
              context: path.resolve(process.cwd(), 'assets/images'),
              noErrorOnMissing: true,
            },
            {
              from: '*.svg',
              to: '__theme/images/icons/[name][ext]',
              context: path.resolve(process.cwd(), 'assets/images/icons'),
              noErrorOnMissing: true,
            },
            {
              from: '**/*.{woff,woff2,eot,ttf,otf}',
              to: '__theme/fonts/[path][name][ext]',
              context: path.resolve(process.cwd(), 'assets/fonts'),
              noErrorOnMissing: true,
            },
            
          ],
        }),
      ]
  }
      

  return config

}

