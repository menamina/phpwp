const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry(),
		'admin-settings/index': path.resolve( process.cwd(), 'src', 'admin-settings', 'index.js' ),
	},
};
