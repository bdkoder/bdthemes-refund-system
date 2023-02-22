<?php

namespace Bdthemes\RefundSystem\Admin;

/**
 * Description of Refunds
 *
 * @author Shahidul Islam
 */
class Refunds
{
    public function plugin_page()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        switch ($action) {
            case 'settings':
                $template = __DIR__ . '/views/settings.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/event-view.php';
                break;

            default:
                $template = __DIR__ . '/views/refund-list.php';
                break;
        }

        if (file_exists($template)) {
            include $template;
        }
    }
    public function delete_refund()
    {
        if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bdt-refund-delete')) {
            wp_die('Are you cheating?');
        }

        if (!current_user_can('manage_options')) {
            wp_die('Are you cheating?');
        }

        $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        if (delete_refund_request($id)) {
            $redirected_to = admin_url('admin.php?page=bdthemes-refund-system&event-deleted=true');
        } else {
            $redirected_to = admin_url('admin.php?page=bdthemes-refund-system&event-deleted=false');
        }

        wp_redirect($redirected_to);
        exit();
    }
}
