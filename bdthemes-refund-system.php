<?php

/**
 * Plugin Name: Bdthemes Refund System
 * Description: A simple description of our plguin
 * Plugin URI: https://bdthemes.com/
 * Author: bdthemes.com
 * Author URI: https://bdthemes.com/
 * Version: 1.5.0
 * License: GPL2
 * Text Domain: refund-system
 */
/**
 * Copyright (c) 2014 Shahidul Islam (email: bdkoder@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */
// don't call the file directly
if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugins class
 */
final class Bdthemes_Refund_System
{

    /**
     * Plugin version
     * @var string
     */
    const version = '1.5.0';

    /**
     * class constructor
     */
    private function __construct()
    {
        $this->define_constants();

        register_activation_hook(__FILE__, [$this, 'activate']);

        add_action('plugins_loaded', [$this, 'init_plugin'], 9);
    }

    /**
     * Initializes a singleton instance
     * @staticvar boolean $instance
     * @return \Bdthemes_Refund_System
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        new Bdthemes\RefundSystem\Api\Request_List_Public('Bdthemes Refund System', BDT_REFUND_SYSTEM_VERSION);

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants()
    {
        define('BDT_REFUND_SYSTEM_FILE', __FILE__);
        define('BDT_REFUND_SYSTEM__PATH', plugin_dir_path(BDT_REFUND_SYSTEM_FILE));
        define('BDT_REFUND_SYSTEM_VERSION', self::version);
        define('BDT_REFUND_SYSTEM_PATH', __DIR__);
        define('BDT_REFUND_SYSTEM_URL', plugins_url('', BDT_REFUND_SYSTEM_FILE));
        define('BDT_REFUND_SYSTEM_ASSETS', BDT_REFUND_SYSTEM_URL . '/assets/');
        // echo BDT_REFUND_SYSTEM_URL;
        // echo BDT_REFUND_SYSTEM__PATH;
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function init_plugin()
    {
        if (is_admin()) {
            new Bdthemes\RefundSystem\Admin();
        } else {
            // new for frontEnd
            // new Bdthemes\RefundSystem\Frontend();
        }

        if (did_action('elementor/loaded')) {
            require_once BDT_REFUND_SYSTEM__PATH . 'plugin.php';
        }
        add_action('wp_ajax_bdt_rs_form', 'bdt_rs_form');
        add_action('wp_ajax_nopriv_bdt_rs_form', 'bdt_rs_form');
    }

    /**
     * Do stuff upon plugin
     *
     * @return void
     */
    public function activate()
    {
        $installer = new Bdthemes\RefundSystem\Installer();
        $installer->run();
    }
}

/**
 *
 * @return \Bdthemes_Refund_System
 */
function bdt_refund_system()
{
    return Bdthemes_Refund_System::init();
}

// kick-off the plugin
bdt_refund_system();
