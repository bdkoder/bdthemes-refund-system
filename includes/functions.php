<?php

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
     * Fetch Refunds
     *
     * @param $args
     *
     * @return array
     */

    public function get_refunds($args = []) {
        global $wpdb;

        $defaults = [
            'number' => 20,
            'offset' => 0,
            'orderby' => 'id',
            'order' => 'DESC',
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
     * Get the count of total Refunds
     *
     * @return int
     */
    public function get_refunds_count() {
        global $wpdb;
        return (int) $wpdb->get_var("SELECT count(id) FROM {$wpdb->prefix}bdthemes_refunds");
    }


    /**
     * Fetch a single row from the DB
     *
     * @param int $id
     *
     * @return object
     */
    public function get_refund($id) {
        global $wpdb;
        return $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}bdthemes_refunds WHERE id = %d", $id)
        );
    }


    /**
     * Insert Refund
     *
     * @param array $form_data
     *
     * @return int|WP_Error
     */
    public function insert_refund($form_data) {
        global $wpdb;

        if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bdt-rs-form-submit')) {
            echo wp_json_encode('nonce_expired');
            wp_die();
        }

        if (empty($form_data['product_license'])) {
            return new \WP_Error('no-license', __('You must provide a License.', 'bdthemes-refunds-system'));
        }

        $license_verify = $this->license_verify($form_data['product_license']);

        if ('error' == $license_verify) {
            $response = [
                'status' => 'error',
                'msg' => 'License is Invalid!'
            ];
            echo wp_json_encode($response);
            wp_die();
        }

        $check_exists = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}bdthemes_refunds WHERE product_license = %d", $form_data['product_license'])
        );

        if ($check_exists) {
            $response = [
                'status' => 'error',
                'msg'    => 'You already applied for a Refund request. We are looking into it. Sometimes processing takes up to 7 working days. Thank you.'
            ];
            echo wp_json_encode($response);
            wp_die();
        }

        $product_name = $license_verify['product_name'] . ' (' . $license_verify['license_title'] . ')';
        $expiry_time  = strtotime($license_verify['expiry_time']);
        $today = strtotime(date("Y-m-d H:i:s"));

        if ($today >=  $expiry_time && 'U' !== $license_verify['has_support']) {
            $response = [
                'status' => 'error',
                'msg'    => 'Sorry, the Refund period time (30 days) expired! The purchase date of your product was - ' . date('d M, Y', $expiry_time)
            ];
            echo wp_json_encode($response);
            wp_die();
        }

        // $form_data['product_name'] = $product_name;

        $defaults = [
            'product_name'    => '',
            'product_license' => '',
            'name'            => '',
            'email'           => '',
            'message'         => NULL,
            'comments'        => NULL,
            'status'          => 'waiting',
            'status_by'       => NULL,
            'created_at'      => current_time('mysql'),
        ];

        $args = [
            'product_name'    => $product_name,
            'product_license' => !empty($form_data['product_license']) ? sanitize_text_field($form_data['product_license']) : NULL,
            'name'            => !empty($form_data['name']) ? sanitize_text_field($form_data['name']) : NULL,
            'email'           => !empty($form_data['email']) ? sanitize_text_field($form_data['email']) : NULL,
            'message'         => !empty($form_data['message']) ? sanitize_text_field($form_data['message']) : NULL,
            'comments'        => !empty($form_data['comments']) ? sanitize_text_field($form_data['comments']) : NULL,
            'status'          => 'waiting',
            'status_by'       => NULL,
            'created_at'      => current_time('mysql'),
        ];

        unset($form_data['action']);
        unset($form_data['_wpnonce']);
        unset($form_data['_wp_http_referer']);

        $data = wp_parse_args($args, $defaults);

        $inserted = $wpdb->insert(
            $wpdb->prefix . 'bdthemes_refunds',
            $data,
            [
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            ]
        );

        if (!$inserted) {
            echo wp_json_encode([
                'status' => 'error',
                'msg'    => 'Something wrong, please contact us - support@bdthemes.com'
            ]);
            wp_die();
        }

        echo wp_json_encode([
            'status' => 'success',
            'msg'    => 'The refund request was submitted successfully.'
        ]);
        wp_die();
    }

    /**
     * License Verify
     *
     * @param array $license
     *
     * @return int|WP_Error
     */
    public function license_verify($license) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $this->CURLOPT_URL . 'license/view',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => array(
                'api_key'          => $this->API_KEY,
                'license_code'     => $license,
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        curl_close($curl);

        if (($response['status'] !== true) || (!isset($response['data']) || (isset($response['data']) && empty($response['data'])))) {
            return 'error';
        }

        $response = $response['data'];
        return $response;
    }

    /**
     * Delete Refund Request
     *
     * @param int $id
     *
     * @return int|boolean
     */

    function __wd_delete_event($id) {
        global $wpdb;
        return $wpdb->delete(
            $wpdb->prefix . 'bdthemes_refunds',
            ['id' => $id],
            ['%d']
        );
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

    public function get_info($data) {

        $response = 'success';
        echo wp_json_encode($response);
        wp_die();
    }

    /**
     * Detect Clients Info by Email/License
     * If Email then will call the License List method
     * If License then call the License Details method
     *
     * @param [type] $data
     * @return void
     */
    public function detect_info($data) {

        if (empty($data['license'])) {
            $msg = 'field-blank';
            echo wp_json_encode($msg);
            wp_die();
        }

        // if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'check-info')) {
        //     echo wp_json_encode('nonce_expired');
        //     wp_die();
        //     // $this->throw_error();
        // }

        $result = $this->license_details(sanitize_text_field($_POST["license"]), intval($_POST['id']));

        echo wp_json_encode($result);
        wp_die();
    }

    /**
     * Get the details of License
     *
     * @return void
     */
    public function license_details($license, $id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $this->CURLOPT_URL . 'license/view',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => array(
                'api_key'          => $this->API_KEY,
                'license_code'     => $license,
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        curl_close($curl);

        if (($response['status'] !== true) || (!isset($response['data']) || (isset($response['data']) && empty($response['data'])))) {
            return 'error';
        }

        $response = $response['data'];

        /**
         * Get Information of Clients
         */
        $client_info = false;
        if (isset($response['client_id'])) {
            $client_info = $this->get_client_info($response['client_id']);
        }


        $status = '';
        if ($response['status'] == 'A') {
            $status = '<span class="bdt-text-success bdt-text-bold"> Active </span>';
        } elseif ($response['status'] == 'R') {
            $status = '<span class="bdt-text-danger bdt-text-bold"> Refunded </span>';
        } elseif ($response['status'] == 'I') {
            $status = '<span class="bdt-text-warning bdt-text-bold"> In-Active </span>';
        } elseif ($response['status'] == 'W') {
            $status = '<span class="bdt-text-success bdt-text-bold"> Free </span>';
        } else {
            $status = '<span class="bdt-text-danger bdt-text-bold" bdt-title="Please Contact License Manager."> Unknown Error </span>';
        }

        $market = '';
        if ($response['market'] == 'E') {
            $market = '<span class="bdt-text-success bdt-text-bold"> Envato </span>';
        } elseif ($response['market'] == 'J') {
            $market = '<span class="bdt-text-danger bdt-text-bold"> JVZoo </span>';
        } elseif ($response['market'] == 'F') {
            $market = '<span class="bdt-text-warning bdt-text-bold"> FastSpring </span>';
        } elseif ($response['market'] == 'P') {
            $market = '<span class="bdt-text-success bdt-text-bold"> Paddle </span>';
        } elseif ($response['market'] == 'W') {
            $market = '<span class="bdt-text-success bdt-text-bold"> WooCommerce </span>';
        } else {
            $market = '<span class="bdt-text-danger bdt-text-bold" bdt-title="Please Contact License Manager."> Unknown </span>';
        }

        $has_support = '';
        if ($response['has_support'] == 'U') {
            $has_support = '<span class="bdt-text-success bdt-text-bold"> Lifetime </span>';
        } elseif ($response['has_support'] == 'Y') {
            $has_support = '<span class="bdt-text-success bdt-text-bold"> Yes </span>';
        } else {
            $has_support = '<span class="bdt-text-danger bdt-text-bold" bdt-title="Please Contact License Manager."> Unknown </span>';
        }

        $email = $this->get_client_email($response['client_id']);
        $row_data = $this->get_refund($id);

        $result = '';

        /** 
         * Inject Clients Personal Information
         */
        if ($client_info !== false) {
            $result .= $client_info;
        }

        $result .= '<h3 class="bdt-margin bdt-padding-small bdt-text-center">License Information</h3>';

        $result .= '<table class="bdt-table bdt-table-striped">
                    <tbody>
                        <tr>
                            <td>
                                <strong>License Code</strong>
                            </td>
                            <td colspan="3">' . $response['purchase_key'] . ' (' . $status . ')</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Product Name</strong>
                            </td>
                            <td colspan="3"><strong class="bdt-text-success"> ' . $response['product_name'] . ' </strong><i>(' . $response['license_title'] . ')</i></td>
                        </tr>
                        <tr>
                            <td>
                            <strong>Buy From</strong>
                            </td>
                            <td>' . $market . '</td>
                            <td>
                            <strong>Support</strong>
                            </td>
                            <td>' . $has_support . '</td>
                        </tr>
                        <tr>
                            <td>
                            <strong>Purchase Date</strong>
                            </td>
                            <td>' . date('d M, Y', strtotime($response['entry_time'])) . '</td>
                            <td>
                            <strong>Expire Date</strong>
                            </td>
                            <td>' . ($response['expiry_time'] !== null ? date('d M, Y', strtotime($response['expiry_time'])) : 'Lifetime') . '</td>
                        </tr>
                        <tr>
                            <td>
                            <strong>Support End</strong>
                            </td>
                            <td>' . ($response['support_end_time'] !== null ? date('d M, Y', strtotime($response['support_end_time'])) : 'Lifetime') . '</td>
                            <td>
                            <strong>Max Domain</strong>
                            </td>
                            <td>' . $response['max_domain'] . '</td>
                        </tr>
                    </tbody>
                </table>';

        $result .= '<h3 class="bdt-margin bdt-padding-small bdt-text-center">Action</h3>';
        $result .= '<div class="bdt-margin">
                <label class="bdt-form-label" for="bdt-rs-action-select">License Action</label>
                <div class="bdt-form-controls bdt-margin-top-small">
                    <select class="bdt-select bdt-padding-remove" id="bdt-rs-action-select" data-id="' . $row_data->id . '">
                        <option>Select Action</option>
                        <option value="waiting">Waiting</option>
                        <option value="declined">Declined</option>
                        <option value="approved">Approved</option>
                        <option value="' . $row_data->status . '" selected>' . ucwords($row_data->status) . '</option>
                    </select>
                </div>
            </div>';
        $result .= '<div class="bdt-margin">
            <textarea class="bdt-textarea" rows="5" placeholder="Comments/Note" id="bdt-rs-comments">' . $row_data->comments . '</textarea>
        </div>
        <button class="bdt-button bdt-button-primary" type="button" id="bdt-rs-action-submit">Update</button>';

        return $result;
    }

    /**
     * Get Client Email
     * 
     * @param mixed $client_id
     */

    public function get_client_email($client_id) {
        if (empty($client_id)) {
            $this->throw_error();
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->CURLOPT_URL . 'client/view',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'api_key' => $this->API_KEY,
                'client_id' => $client_id,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);

        if ($response['status'] !== true) {
            $this->throw_error();
        }

        if ($response['data']['email']) {
            return $response['data']['email'];
        }
    }

    /**
     * Get Information of Client
     *
     * @return void
     */
    public function get_client_info($client_id) {
        if (empty($client_id)) {
            $this->throw_error();
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->CURLOPT_URL . 'client/view',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'api_key' => $this->API_KEY,
                'client_id' => $client_id,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);

        if ($response['status'] !== true) {
            return false;
        }

        $status = '';

        if ($response['data']['status'] == 'A') {
            $status = '<span class="bdt-text-success bdt-text-bold"> Active </span>';
        } else {
            $status = '<span class="bdt-text-danger bdt-text-bold"> In-active </span>';
        }

        $result = '<h3 class="bdt-margin bdt-padding-small bdt-text-center">Client Personal Information</h3>';
        $result .= '<table class="bdt-table bdt-table-striped">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Client Status</strong>
                                </td>
                                <td colspan="3">' . $status . '</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Name</strong>
                                </td>
                                <td>' . $response['data']['name'] . '</td>
                                <td>
                                    <strong>Email</strong>
                                </td>
                                <td>' . $response['data']['email'] . '</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Entry Time</strong>
                                </td>
                                <td>' . $response['data']['entry_time'] . '</td>
                                <td>
                                    <strong>Company</strong>
                                </td>
                                <td>' . $response['data']['company'] . '</td>
                            </tr>
                        </tbody>
                    </table>';
        return $result;
    }
    /**
     * Get the Name of Product
     * By ID & License
     *
     * @return void
     */
    public function product_details($license, $product_id) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $this->CURLOPT_URL . 'product/view',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,

            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,

            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => array(
                'api_key'          => $this->API_KEY,
                'license_code'     => $license,
                'product_id'       => $product_id,
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response, true);

        curl_close($curl);

        return $response['data'];
    }

    /**
     * Action Trigger
     *
     * @return void
     */

    public function action_trigger($data) {

        //todo

        //    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bdt_rs_action_nonce')) {
        //         echo wp_json_encode('nonce_expired');
        //         wp_die();
        //     }

        $id = intval($data['id']);
        global $wpdb;

        $defaults = [
            'status'    => 'waiting',
            'comments'  => NULL,
            'status_by' => get_current_user_id()
        ];
        $args = [
            'status'    => $data['actionValue'],
            'comments'  => !empty($data['comments']) ? $data['comments'] : NULL,
            'status_by' => get_current_user_id()
        ];
        $data = wp_parse_args($args, $defaults);

        $updated = $wpdb->update(
            $wpdb->prefix . 'bdthemes_refunds',
            $data,
            ['id' => $id],
            [
                '%s',
                '%s',
                '%d',
            ],
            ['%d']
        );

        if ($updated) {
            $response = 'success';
        } else {
            $response = 'failed';
        }

        echo wp_json_encode($response);
        wp_die();
    }
}

/**
 * Save Settings
 *
 */
function bdt_rs_save_settings() {
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    $bdt_rs_app->save_settings($_POST);
}

/**
 * Get Refunds
 *
 */
function bdt_rs_get_refunds() {
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    return $bdt_rs_app->get_refunds($_POST);
}

/**
 * Get Counts Refund
 *
 */
function bdt_rs_get_refunds_count() {
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    return $bdt_rs_app->get_refunds_count($_POST);
}

/**
 * Get License Info
 *
 */
function bdt_rs_get_info() {
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    $bdt_rs_app->detect_info($_POST);
}

/**
 * Update Action Trigger
 *
 */
function bdt_rs_action_trigger() {
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    $bdt_rs_app->action_trigger($_POST);
}

/**
 * Submit Form
 *
 */
function bdt_rs_form() {
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    return $bdt_rs_app->insert_refund($_POST);
}
