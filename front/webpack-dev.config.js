const webpack = require('webpack');
const path = require('path');

module.exports = {
  entry: {
    app: './boot.js'
  },
  output: {
    path: path.join(__dirname, '/dist'),
    filename: '[name].bundle.js',
    publicPath: '/dist/',
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
            js: 'babel-loader?presets=env'
          }
        }
      },
      { test: /iview.src.*?js$/, loader: 'babel-loader' },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
      },
      { test: /\.(png|jpg|gif|svg|ttf|eot|woff)$/, loader: 'file-loader?name=[name].[hash].[ext]'},
      { test: /\.css$/, loader: 'style-loader!css-loader' },
    ]
  },
  // resolve: {
  //   alias: {
  //     jquery: path.join(__dirname,'libs/jquery-3.2.1.min.js'),
  //     wangEditor: path.join(__dirname,'libs/wangEditor-2.1.23/dist/js/wangEditor.min.js'),
  //     'vue': 'vue/dist/vue.js',
  //   }
  // },
  plugins: [
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': JSON.stringify('develope')
    })
  ],
  devServer:{
    inline: true,
    port: 805,
    // contentBase: './www',
    proxy: {
      '/api/*': {
        target: 'http://localhost:85',
        secure: false
      }
    }
  }
}