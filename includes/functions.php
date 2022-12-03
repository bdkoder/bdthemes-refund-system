<?php

/**
 * Insert a new Event
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function wd_event_insert_event($args = []) {
    global $wpdb;

    if (empty($args['name'])) {
        return new \WP_Error('no-name', __('You must provide a name.', 'bdthemes-refunds-system'));
    }

    $defaults = [
        'name' => '',
        'date' => '',
        'created_by' => get_current_user_id(),
        'created_at' => current_time('mysql'),
    ];

    $data = wp_parse_args($args, $defaults);

    if (isset($data['id'])) {

        $id = $data['id'];
        unset($data['id']);

        $updated = $wpdb->update(
            $wpdb->prefix . 'bdthemes_refunds',
            $data,
            ['id' => $id],
            [
                '%s',
                '%s',
                '%d',
                '%s',
            ],
            ['%d']
        );

        return $updated;
    } else {

        $inserted = $wpdb->insert(
            $wpdb->prefix . 'bdthemes_refunds',
            $data,
            [
                '%s',
                '%s',
                '%d',
                '%s',
            ]
        );

        if (!$inserted) {
            return new \WP_Error('failed-to-insert', __('Failed to insert data', 'bdthemes-refunds-system'));
        }
    }

    return $wpdb->insert_id;
}

/**
 * Fetch Events
 *
 * @param $args
 *
 * @return array
 */

function wd_get_refunds($args = []) {
    global $wpdb;

    $defaults = [
        'number' => 20,
        'offset' => 0,
        'orderby' => 'id',
        'order' => 'ASC',
    ];

    $args = wp_parse_args($args, $defaults);

    $items = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}bdthemes_refunds
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %d, %d",
            $args['offset'],
            $args['number']
        )
    );

    return $items;
}

/**
 * Get the count of total events
 *
 * @return int
 */
function wd_refunds_count() {
    global $wpdb;
    return (int) $wpdb->get_var("SELECT count(id) FROM {$wpdb->prefix}bdthemes_refunds");
}

/**
 * Fetch a single contact form the DB
 *
 * @param int $id
 *
 * @return object
 */
function wd_get_event($id) {
    global $wpdb;
    return $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}bdthemes_refunds WHERE id = %d", $id)
    );
}

/**
 * Delete and Event
 *
 * @param int $id
 *
 * @return int|boolean
 */

function wd_delete_event($id) {
    global $wpdb;
    return $wpdb->delete(
        $wpdb->prefix . 'bdthemes_refunds',
        ['id' => $id],
        ['%d']
    );
}


$get_option = get_option('bdts_settings');
if (isset($get_option['api_key'])) {
    define("API_KEY", $get_option['api_key']);
} else {
    define("API_KEY", 'XXXX-XXXX-XXXXXXX-XXXXXXXX');
}
if (isset($get_option['api_end_point'])) {
    define("API_ENDPOINT", $get_option['api_end_point']);
} else {
    define("API_ENDPOINT", 'https://test.com/wp-json/api/');
}


class BDT_REFUND_SYSTEM_APP {
    public $CURLOPT_URL = API_ENDPOINT;
    public $API_KEY     = API_KEY;

    public function __construct() {
    }

    public function throw_error() {
        $msg = 'error';
        echo wp_json_encode($msg);
        wp_die();
    }

    /**
     * Save Settings
     *
     * @return void
     */

    public function save_settings($data) {
        /* hit bottom of screen event  */

        $option    = 'bdts_settings';
        $new_value = new stdClass();
        $new_value = $data;

        // print_r($new_value);

        // if ((!isset($option) || $option == '') || (!isset($new_value) || $new_value = '')) {

        //     $response = 'error';
        //     echo wp_json_encode($response);
        //     wp_die();
        // }

        // print_r($new_value);
        // exit();

        update_option($option, $new_value);

        $response = 'success';
        echo wp_json_encode($response);
        wp_die();
    }
}

function bdt_rs_save_settings() {
    $bdts_app = new BDT_REFUND_SYSTEM_APP();
    $bdts_app->save_settings($_POST);
}
