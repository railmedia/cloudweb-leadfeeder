const { merge } = require('webpack-merge');
const TerserPlugin = require('terser-webpack-plugin');
const CompressionPlugin = require('compression-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const autoprefixer = require('autoprefixer');
const PostCSSMixins = require('postcss-mixins');
const PostCSSNested = require('postcss-nested');
const PostCSSSimpleVars = require('postcss-simple-vars');
const CSSMQpacker = require('css-mqpacker');
const PixRem = require('pixrem');
const common = require('./common');

module.exports = merge(common, {
	mode: 'production',
	devtool: 'source-map',
	module: {
		rules: [
			...common.module.rules,
			// compile all .scss files to plain old css
			{
				test: /\.s[c|a]ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					{
						loader: 'css-loader',
						options: { sourceMap: true, url: false },
					},
					{
						loader: 'postcss-loader',
						options: {
							// ident: 'postcss',
							sourceMap: true,
							postcssOptions: {
								// plugins: () => [
								plugins: [
									autoprefixer(),
									PostCSSNested(),
									PostCSSSimpleVars(),
									PostCSSMixins(),
									CSSMQpacker({
										sort: true,
									}),
									PixRem({
										atrules: true,
										replace: false,
									}),
								],
							}
						},
					},
					{
						loader: 'sass-loader',
						options: {
							sourceMap: false,
							sassOptions: {
								outputStyle: 'compressed',
							},
						},
					},
				],
			},
		],
	},
	optimization: {
		minimize: true,
		minimizer: [
			// enable the js minification plugin
			new TerserPlugin({
				terserOptions: {
					compress: {
						warnings: false,
					},
					output: {
						comments: false,
					},
				},
				// sourceMap: true,
			}),
		],
	},
	plugins: [
		...common.plugins,
		new CompressionPlugin({
			algorithm: 'gzip',
		}),
	],
});
