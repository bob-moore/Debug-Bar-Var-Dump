<?php
/**
 * @wordpress-plugin
 * Plugin Name: Debug Bar Var_Dump
 * Plugin URI:  https://www.bobmoore.dev
 * Description: Debug Bar Addons
 * Version:     0.0.1
 * Author:      Bob Moore
 * Author URI:  https://www.bobmoore.dev
 * Depends:     Debug Bar
 * Text Domain: devkit-debug-bar
 * Domain Path: /languages
 */

use \DevKit\DebugBar\VarDump\Plugin;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/autoload.php';

$Devkit_Debug_Vardump = new Plugin();

function debug_dump( $data, $manual_backtrace = [] ) : void
{
    Plugin::log( $data, $manual_backtrace );
}
