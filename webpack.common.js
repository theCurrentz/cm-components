const path = require('path')
//const HtmlWebpackPlugin = require('html-webpack-plugin')
const webpack = require("webpack")
const CleanWebpackPlugin = require('clean-webpack-plugin')
const autoprefixer = require('autoprefixer')
const MiniCSSExtractPlugin = require("mini-css-extract-plugin")
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

module.exports = {
    entry: {
        app: './src/index.js',
        errorcatcher: './components/error-reporting/js/error-catcher.js',
        quiz: './components/quiz/src/quiz-app.js',
    },
    output: {
        filename: '[name].js',
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
        new MiniCSSExtractPlugin({
            filename: "cmquiz.css",
        }),
        new webpack.LoaderOptionsPlugin({
          options: {
            postcss: [
              autoprefixer()
            ]
          }
        })
    ],
    module: {
        rules: [
            {
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
                        presets: ['env']
                    }
                  }
              ]
            },
            {
                test: /\.(sa|sc|c)ss$/,
                exclude: /node_modules/,
                use: [
                  MiniCSSExtractPlugin.loader,
                  "css-loader",
                  "postcss-loader",
                  "sass-loader"
                ]
            },
        ]
    }
};
