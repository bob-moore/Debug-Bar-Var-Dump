const path = require('path');
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

module.exports = (env, argv) => {
    return {
        entry :
            {
                'app' : './src/scripts/main.ts'
            },
        output :
        {
            filename : '[name].js',
            path : path.resolve(__dirname, 'dist/scripts'),
        },
        devtool : 'eval-cheap-source-map',
        watchOptions:
        {
            ignored : '**/node_modules/',
        },
        resolve :
        {
            extensions: ['.ts', '.tsx', '.js' ]
        },
        module : 
        {
            rules : 
            [
                {
                    test : /.js$/,
                    exclude : /node_modules/,
                    use : 
                    {
                        loader : 'babel-loader',
                        options : 
                        {
                            presets : ["@wordpress/babel-preset-default"],
                            plugins : ["@babel/plugin-proposal-object-rest-spread"]
                        }
                    }
                },
                {
                    test : /\.(ts|tsx)$/,
                    exclude : /node_modules/,
                    use : 
                    {
                        loader: 'ts-loader',
                    }
                }
            ]
        },
        plugins: [ new DependencyExtractionWebpackPlugin() ]
    }
};