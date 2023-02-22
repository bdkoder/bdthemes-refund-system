<?php

/**
 * Description of Menu
 *
 * @author Shahidul Islam
 */

namespace Bdthemes\RefundSystem\Admin;

class Menu {
    public $events;

    public function __construct($events) {
        $this->events = $events;
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Register admin menu
     * 
     * @return void
     */
    public function admin_menu() {
        $parent_slug = 'bdthemes-refund-system';
        $capability = 'manage_options';

        add_menu_page(__('Refund System', 'bdthemes-refund-system'), __('Refund System', 'bdthemes-refund-system'), $capability, $parent_slug, [$this->events, 'plugin_page'], 'dashicons-editor-unlink');

        add_submenu_page($parent_slug, __('Settings', 'bdthemes-refund-system'), __('Settings', 'bdthemes-refund-system'), $capability, $parent_slug . '&action=settings', [$this->events, 'plugin_page']);
    }
}
