<?php

/**
 * Plugin Name:     Plugin Name
 * Plugin URI:      https://aaronsly.com
 * Description:     Plugin Description
 * Author:          Aaron Sly
 * Author URI:      https://aaronsly.com
 * Text Domain:     plugin-domain
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         WP_PLUGIN_NAME
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class WP_PLUGIN_NAME
{

    // A reference to an instance of this class.
    private static $instance;

    public $plugin;

    // Initializes the plugin by setting filters and administration functions.
    function __construct()
    {

        $this->plugin = plugin_basename(__FILE__);
    }

    // Returns an instance of this class. 
    public static function get_instance()
    {

        if (null == self::$instance) {
            self::$instance = new WP_PLUGIN_NAME();
        }

        return self::$instance;
    }

    public function activate()
    {
        flush_rewrite_rules();
    }

    public function deactivate()
    {
        flush_rewrite_rules();
    }

    public function register()
    {
        echo "Hello World!";
        add_action('action_name', [$this, 'callback']);
        add_filter('filter_name', [$this, 'callback_2']);
        
    }

}

add_action('plugins_loaded', array('WP_PLUGIN_NAME', 'get_instance'));

if (class_exists('SSD_OVERRRIDES')) {
    $WP_PLUGIN_NAME = new WP_PLUGIN_NAME();
    $WP_PLUGIN_NAME->register();

    // Activation
    register_activation_hook(__FILE__, array($WP_PLUGIN_NAME, 'activate'));

    // Deactivation
    register_deactivation_hook(__FILE__, array($WP_PLUGIN_NAME, 'deactivate'));
}
