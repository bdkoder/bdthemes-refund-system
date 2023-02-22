<?php

namespace Bdthemes\RefundSystem;

/**
 * The admin class
 */
class Admin
{
    public function __construct()
    {
        $refunds = new Admin\Refunds();
        $this->dispatch_actions($refunds);

        new Admin\Menu($refunds);
    }

    public function dispatch_actions($refunds)
    {

        add_action('wp_ajax_bdt_rs_get_info', 'bdt_rs_get_info');
        add_action('wp_ajax_bdt_rs_action_trigger', 'bdt_rs_action_trigger');

        if (isset($_GET['page']) && ($_GET['page'] == 'bdthemes-refund-system')) {
            add_action('admin_enqueue_scripts', [$this, 'load_admin_scripts']);
        }

        add_action('wp_ajax_bdt_rs_save_settings', 'save_settings');

        add_action('admin_post_bdt-refund-delete', [$refunds, 'delete_refund']);

    }

    public function load_admin_scripts()
    {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        wp_enqueue_script('bdt-refund-system-js', BDT_REFUND_SYSTEM_ASSETS . '/js/admin/refund-system.js', ['jquery'], false, true);
        wp_localize_script('bdt-refund-system-js', 'bdt_rs_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);

        wp_enqueue_style('bdt-refund-system-css', BDT_REFUND_SYSTEM_ASSETS . '/css/admin/refund-system.css', [], false, 'all');

        wp_enqueue_style('bdt-uikit', 'https://bdthemes.com/wp-content/plugins/bdthemes-element-pack/assets/css/bdt-uikit.css', [], '3.13.1');
        wp_enqueue_script('bdt-uikit', 'https://bdthemes.com/wp-content/plugins/bdthemes-element-pack/assets/js/bdt-uikit.min.js', ['jquery'], '3.13.1', true);

        wp_enqueue_style('sweetalert2-css', '//cdn.jsdelivr.net/npm/sweetalert2@11.4.20/dist/sweetalert2.min.css', [], '');
        wp_enqueue_script('sweetalert2-js', '//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js', ['jquery'], '', true);
    }
}
