<?php

namespace Bdthemes\RefundSystem;

/**
 * The Installer class
 */
class Installer {
    /**
     * Runt the installer
     * 
     * @return void
     */

    public function run() {
        $this->add_version();
        $this->create_tables();
    }

    public function add_version() {
        $installed = get_option('bdthemes_refund_system_installed');

        if (!$installed) {
            update_option('bdthemes_refund_system_installed', time());
        }

        update_option('bdthemes_refund_system_version', BDT_REFUND_SYSTEM_VERSION);
    }

    /**
     * Create nessary database tables
     * 
     * @return void
     */
    public function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}bdthemes_refunds` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `product_name` VARCHAR(255) NULL DEFAULT NULL,
            `product_license` VARCHAR(255) NULL DEFAULT NULL,
            `name` VARCHAR(255) NULL DEFAULT NULL,
            `email` VARCHAR(255) NULL DEFAULT NULL,
            `message` MEDIUMTEXT NULL DEFAULT NULL,
            `comments` VARCHAR(255) NULL DEFAULT NULL,
            `status` VARCHAR(20) NULL DEFAULT NULL,
            `status_by` BIGINT(20) NULL DEFAULT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate";

        if (!function_exists('dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta($schema);
    }
}
