<?php

namespace Bdthemes\RefundSystem\Admin;

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List Table Class
 *
 * @author Shahidul Islam
 */
class Refund_List extends \WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular' => 'event',
            'plural' => 'events',
            'ajax' => 'false',
        ]);
    }

    public function get_columns()
    {
        return [
            'cb' => '<input type="checkbox"/>',
            'name' => __('Name', 'bdthemes-refund-system'),
            'product_license' => __('License', 'bdthemes-refund-system'),
            'status' => __('Status', 'bdthemes-refund-system'),
            'created_at' => __('Created at', 'bdthemes-refund-system'),
        ];
    }

    public function get_sortable_columns()
    {
        $sortable_columns = [
            'name' => ['name', true],
            'product_license' => ['product_license', true],
            'status' => ['status', true],
            'created_at' => ['created_at', true],
        ];

        return $sortable_columns;
    }

    protected function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'value':
                break;
            default:
                return isset($item->$column_name) ? $item->$column_name : '';
        }
    }

    public function column_name($item)
    {
        $actions = [];

        $actions['details'] = sprintf(
            '<a class="bdt-license-action" data-id="%s" href="javascript:void(0);" data-license="%s" title="%s">%s</a>',
            $item->id,
            $item->product_license,
            $item->id,
            __('Details & Action', 'bdthemes-refund-system'),
            __('Details & Action', 'bdthemes-refund-system')
        );

        if ('waiting' == $item->status) {
            $actions['delete'] = sprintf('<a href="%s" class="submitdelete" onclick="return confirm(\'Are you sure?\');" title="%s">%s</a>', wp_nonce_url(admin_url('admin-post.php?action=bdt-refund-delete&id=' . $item->id), 'bdt-refund-delete'), $item->id, __('Delete', 'bdthemes-refund-system'), __('Delete', 'bdthemes-refund-system'));
        }

        return sprintf(
            '<a href="%1$s"><strong>%2$s</strong></a> %3$s',
            admin_url('admin.php?page=bdthemes-refund-system&action=view&id' . $item->id),
            $item->name,
            $this->row_actions($actions)
        );
    }

    public function column_product_license($item)
    {
        return sprintf(
            '<strong>Product - </strong><i>%1$s</i> <br> <strong>License - </strong><i>%2$s</i> <br> <strong>Email - </strong><i id="submit-email-%6$s">%3$s</i> <br> <strong>Message - %4$s</strong><br> <strong>Comments - %5$s</strong>',
            $item->product_name,
            $item->product_license,
            $item->email,
            $item->message,
            $item->comments,
            $item->id
        );
    }

    public function column_status($item)
    {
        $status = !empty($item->status) ? $item->status : 'waiting';
        return sprintf('<a class="bdt-badge badge-primary bdt-rf-%1$s" href="javascript:void(0);">%2$s</a>', $status, ucwords($status));
    }

    protected function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="event_id[]" value="%d"/>',
            $item->id
        );
    }

    public function prepare_items()
    {
        $column = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$column, $hidden, $sortable];

        $per_page = 20;
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;

        $args = [
            'number' => $per_page,
            'offset' => $offset,
        ];

        if (isset($_REQUEST['orderby']) && isset($_REQUEST['order'])) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order'] = $_REQUEST['order'];
        }

        $this->items = bdt_rs_get_refunds($args);
        $this->set_pagination_args([
            'total_items' => bdt_rs_get_refunds_count(),
            'per_page' => $per_page,
        ]);
    }
}
