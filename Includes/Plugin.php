<?php

namespace DevKit\DebugBar\VarDump;

use WP_REST_Request;
use WP_REST_Response;
use dump;

defined( 'ABSPATH' ) || exit;

class Plugin
{
    /**
     * Base URL for plugin
     * 
     * @access protected
     */
    protected static string $_url;
    /**
     * Base PATH for plugin
     * 
     * @access protected
     */
    protected static string $_path;
    /**
     * Constructor
     * 
     * Setup vars, add actions, and add filters
     */
    public function __construct()
    {
        self::$_url = trailingslashit( plugin_dir_url( __DIR__ ) );
        self::$_path = trailingslashit( plugin_dir_path( __DIR__ ) );

        add_filter( 'debug_bar_panels', [ $this, 'loadPanels' ] );
        add_action( 'rest_api_init', [$this, 'registerRoutes'] );
        add_action( 'wp_enqueue_scripts', [$this, 'enqueueAssets'] );
        add_action( 'admin_enqueue_scripts', [$this, 'enqueueAssets'] );
    }
    /**
     * Add new panel to DebugBar
     * 
     * @param array $panels Collection of panels passed from debug bar
     * @return array
     */
    public function loadPanels( array $panels ) : array
    {
        require_once self::$_path . 'Includes/Panel.php';
        
        $panels[ 'devkit_dump' ] = new \Devkit_Debugbar_Dump_Panel();
        
        return $panels;
    }
    /**
     * Enqueue scripts/styles needed for the plugin to function
     * 
     * @return void
     */
    public function enqueueAssets() : void 
    {
        $assets = include self::$_path . 'dist/scripts/app.asset.php';
        wp_enqueue_script(
            'devkit-debugbar',
            self::$_url . 'dist/scripts/app.js',
            $assets['dependencies'],
            $assets['version'],
            true
        );
    }
    /**
     * Register rest route to get data from JS
     * 
     * @return void
     */
    public function registerRoutes() : void
    {
        register_rest_route( 'debugbar/v2', '/vardumps',
            [
                'methods' => 'GET',
                'callback' => [$this, 'get'],
            ]
        );
    }
    /**
     * Get log data to display in panel
     * 
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function get( WP_REST_Request $request ) : WP_REST_Response
    {
        $log = get_transient( 'devkit_debugbar_dump_log' ) ?: [];

        delete_transient( 'devkit_debugbar_dump_log' );

        return rest_ensure_response( json_encode( $log ) );
    }
    /**
     * Log an item into a transient for later rendering
     * 
     * Capture 'var_dumps'
     * 
     * @param mixed $data anything that needs var_dumped
     * @param array manually set backtrace data - useful for twig files that don't reflect the real file
     * @return void 
     */
    public static function log( $data, array $manual_backtrace = [] ) : void
    {
        $log = get_transient( 'devkit_debugbar_dump_log' ) ?: [];

        $backtrace = debug_backtrace();

        ob_start();
        
        dump( $data );

        $item = [
            'time' => time(),
            'file' => $manual_backtrace['file'] ?? $backtrace[0]['file'] ?? '',
            'data' => ob_get_clean()
        ];

        $log[] = $item;

        set_transient( 'devkit_debugbar_dump_log', $log, 120 );
    }
}