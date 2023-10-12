const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const StyleLintPlugin = require('stylelint-webpack-plugin');
// const targetPath = '../assets/src/scripts';
// const basePath = __dirname;
// const targetFolder = 'build';

module.exports = {
	entry: {
		front: [
			path.resolve('assets/scripts/src', 'front.js'),
			// path.resolve('assets/styles/src', 'front.scss'),
		],
		// admin: [
		// 	path.resolve('assets/scripts/src', 'admin.jsx'),
		// 	path.resolve('assets/styles/src', 'admin.scss')
		// ],
		// singletour: [
		// 	path.resolve('assets/scripts/src', 'single-tour.js')
		// ],
		// singlepost: [
		// 	path.resolve('assets/scripts/src', 'single-post.js')
		// ],
		// magnificpopup: [
		// 	path.resolve('assets/styles/src', 'magnific-popup.scss')
		// ],
		// admin: [
		// 	path.resolve('assets/scripts/src', 'admin.js')
		// ]
		// slick: [
		// 	path.resolve('assets/src/scripts', 'slick.js')
		// ]
	},
	output: {
		filename: '[name].bundle.js',
		path: path.resolve(__dirname, '../assets/scripts/build'),
		publicPath: '/',
	},
	stats: 'errors-only',
	externals: {
		jquery: 'jQuery',
		react: 'React',
		'react-dom': 'ReactDOM',
		// slick: 'slick',
	},
	module: {
		rules: [
			// perform js babelization on all .js files
			{
				test: /\.(js|jsx|tsx)$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: ['@babel/preset-env'],
						cacheDirectory: true,
					},
				},
			},
			{
				test: /\.(png|jpg)$/,
				loader: 'file-loader',
				options: {
					name: '[name].[ext]',
					outputPath: 'assets/images',
				},
			},
			{
				test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
				use: [
					{
						loader: 'file-loader',
						options: {
							name: '[name].[ext]',
							outputPath: 'assets/fonts',
						},
					},
				],
			},
		],
	},
	plugins: [
		new CleanWebpackPlugin(),
		// extract css into dedicated file
		new StyleLintPlugin({
			files: './assets/scripts/src/styles/**/*.s?(a|c)ss',
			fix: true,
			failOnError: false,
			syntax: 'scss',
		}),
		new MiniCssExtractPlugin({
			filename: '../../css/[name].css',
		}),
	],
	devtool: 'cheap-module-source-map',
};
