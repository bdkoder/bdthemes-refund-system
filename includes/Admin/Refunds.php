<?php

namespace Bdthemes\RefundSystem\Admin;

/**
 * Description of Refunds
 *
 * @author Shahidul Islam
 */
class Refunds {
    public function plugin_page() {
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
}
