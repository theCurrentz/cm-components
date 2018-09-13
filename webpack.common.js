const path = require('path')
//const HtmlWebpackPlugin = require('html-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const MiniCSSExtractPlugin = require("mini-css-extract-plugin")
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
    entry: {
        app: './src/index.js',
        errorcatcher: './components/error-reporting/js/error-catcher.js'
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'dist')
    },
    devServer: {
      contentBase: './dist',
      compress: true
    },
    watch: true,
    watchOptions: {
      ignored: /node_modules/,
      poll: 3000 // Check for changes every second
    },
    optimization: {
      minimizer: [
        new UglifyJsPlugin({
          cache: true,
          parallel: true
        }),
        new OptimizeCSSAssetsPlugin({})
      ]
  },
    plugins: [
        new CleanWebpackPlugin(['dist']),
        // new HtmlWebpackPlugin({
        //     title: 'Output Management'
        // }),
        new MiniCSSExtractPlugin({})
    ],
    module: {
        rules: [
            {
              enforce: "pre",
              test: /\.js$/,
              exclude: /node_modules/,
              use: [
                  {
                    loader: "eslint-loader",
                    options: {
                      fix: true,
                      cache: true
                    }
                  },
                  {
                    loader: 'babel-loader',
                    query: {
                        presets: ['es2015']
                    }
                  }
              ]
            },
            {
              test: /\.(sass|scss)$/,
              use: [
                "style-loader",
                "css-loader",
                "sass-loader"
              ]
            },
            {
                test: /\.css$/,
                use: [
                  {
                    loader: MiniCSSExtractPlugin.loader,
                  },
                  'css-loader'
                ]
            },
        ]
    }
};
