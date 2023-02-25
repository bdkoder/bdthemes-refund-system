<?php

$get_option = get_option('bdts_settings');
if (isset($get_option['api_key'])) {
    define('BDT_RS_API_KEY', $get_option['api_key']);
} else {
    define('BDT_RS_API_KEY', 'XXXX-XXXX-XXXXXXX-XXXXXXXX');
}
if (isset($get_option['api_end_point'])) {
    define('BDT_RS_API_ENDPOINT', $get_option['api_end_point']);
} else {
    define('BDT_RS_API_ENDPOINT', 'https://test.com/wp-json/api/');
}
if (isset($get_option['webhook'])) {
    define('BDT_RS_DISCORD_WEBHOOK', $get_option['webhook']);
} else {
    define('BDT_RS_DISCORD_WEBHOOK', false);
}

class BDT_REFUND_SYSTEM_APP
{
    public $CURLOPT_URL = BDT_RS_API_ENDPOINT;
    public $API_KEY = BDT_RS_API_KEY;
    public $DISCORD_WEBHOOK = BDT_RS_DISCORD_WEBHOOK;

    public function __construct()
    {
    }

    public function throw_error()
    {
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

    public function get_refunds($args = [])
    {
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
    public function get_refunds_count()
    {
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
    public function get_refund($id)
    {
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
    public function insert_refund($form_data)
    {
        global $wpdb;

        if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bdt-rs-form-submit')) {
            echo wp_json_encode('nonce_expired');
            wp_die();
        }

        if (empty($form_data['product_license'])) {
            return new \WP_Error('no-license', __('You must provide a License.', 'bdthemes-refunds-system'));
        }

        $license_verify = $this->license_verify(sanitize_text_field(trim($form_data['product_license'])));

        if ('error' == $license_verify) {
            $response = [
                'status' => 'error',
                'msg' => 'License is Invalid!',
            ];
            echo wp_json_encode($response);
            wp_die();
        }

        $check_exists = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}bdthemes_refunds WHERE product_license = %s", sanitize_text_field(trim($form_data['product_license'])))
        );

        if ($check_exists) {
            $response = [
                'status' => 'error',
                'msg' => 'You already applied for a Refund request. We are looking into it. Sometimes processing takes up to 7 working days. Thank you.',
            ];
            echo wp_json_encode($response);
            wp_die();
        }

        $product_name = $license_verify['product_name'] . ' (' . $license_verify['license_title'] . ')';
        $entry_time = strtotime($license_verify['entry_time']);
        $entry_time_with_30 = strtotime($license_verify['entry_time'] . '+30 days');
        $today = strtotime(date("Y-m-d H:i:s"));

        /**
         * Get Information of Clients
         */
        $client_email = false;
        if (isset($license_verify['client_id'])) {
            $client_email = $this->get_client_email($license_verify['client_id']);
        }

        // echo date('d M, Y', $entry_time_with_30);
        // print_r($client_email); exit();

        /**
         * If 30 days expired
         * Will be reject for refund
         */

        //  var_dump($today ,  $entry_time_with_30); exit();

        if ($today > $entry_time_with_30) {
            $response = [
                'status' => 'error',
                'msg' => 'Sorry, the Refund period time (30 days) expired! The purchase date of your product was - ' . date('d M, Y', $entry_time),
            ];
            echo wp_json_encode($response);
            wp_die();
        }

        // $form_data['product_name'] = $product_name;

        $defaults = [
            'product_name' => '',
            'product_license' => '',
            'name' => '',
            'email' => '',
            'message' => null,
            'comments' => null,
            'status' => 'waiting',
            'status_by' => null,
            'created_at' => current_time('mysql'),
        ];

        $args = [
            'product_name' => $product_name,
            'product_license' => !empty($form_data['product_license']) ? sanitize_text_field($form_data['product_license']) : null,
            'name' => !empty($form_data['name']) ? sanitize_text_field($form_data['name']) : null,
            'email' => !empty($form_data['email']) ? sanitize_text_field($form_data['email']) : null,
            'message' => !empty($form_data['message']) ? sanitize_text_field($form_data['message']) : null,
            'comments' => !empty($form_data['comments']) ? sanitize_text_field($form_data['comments']) : null,
            'status' => 'waiting',
            'status_by' => null,
            'created_at' => current_time('mysql'),
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
                'msg' => 'Something wrong, please contact us - support@bdthemes.com',
            ]);
            wp_die();
        }

        echo wp_json_encode([
            'status' => 'success',
            'msg' => 'The refund request was submitted successfully.',
        ]);

        /**
         * Send Email
         */

        $to_emails = [$form_data['email'], $client_email];

        $email_data = [
            'name' => $form_data['name'],
            'email' => $to_emails,
            'subject' => esc_html('Refund Request Submitted Successfully', 'bdthemes-refund-system'),
            'email_templates' => 'request-confirmation.html',
            //
            'icon' => 'refund.png',
        ];

        $this->send_email_automation($email_data);

        /**
         * Send notification to discord
         */
        $this->send_discord_notification([
            'name' => $form_data['name'],
            'email' => $client_email,
            'submit_email' => $form_data['email'],
            'license' => $form_data['product_license'],
            'product_name' => $product_name,
            'refund_reason' => !empty($form_data['message']) ? sanitize_text_field($form_data['message']) : '',
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
    public function license_verify($license)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->CURLOPT_URL . 'license/view',
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
                'license_code' => $license,
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

    public function delete_refund($id)
    {
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

    public function save_settings($data)
    {
        /* hit bottom of screen event  */

        $option = 'bdts_settings';
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

    public function get_info($data)
    {

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
    public function detect_info($data)
    {

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
    public function license_details($license, $id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->CURLOPT_URL . 'license/view',
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
                'license_code' => $license,
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
                    <select class="bdt-padding-remove" id="bdt-rs-action-select" data-id="' . $row_data->id . '">
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
            <textarea class="bdt-textarea bdt-margin-top" rows="5" placeholder="Additional Message with Email" id="bdt-rs-additional-msg"></textarea>
        </div>
        <button class="bdt-button bdt-button-primary" type="button" id="bdt-rs-action-submit">Update</button>';

        return $result;
    }

    /**
     * Get Client Email
     *
     * @param mixed $client_id
     */

    public function get_client_email($client_id)
    {
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
    public function get_client_info($client_id)
    {
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
                                <td id="rf-modal-client-name">' . $response['data']['name'] . '</td>
                                <td>
                                    <strong>Email</strong>
                                </td>
                                <td id="rf-modal-client-email">' . $response['data']['email'] . '</td>
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
    public function product_details($license, $product_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->CURLOPT_URL . 'product/view',
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
                'license_code' => $license,
                'product_id' => $product_id,
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

    public function action_trigger($form_data)
    {

        //todo

        //    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'bdt_rs_action_nonce')) {
        //         echo wp_json_encode('nonce_expired');
        //         wp_die();
        //     }

        $id = intval($form_data['id']);
        global $wpdb;

        $defaults = [
            'status' => 'waiting',
            'comments' => null,
            'status_by' => get_current_user_id(),
        ];
        $args = [
            'status' => $form_data['actionValue'],
            'comments' => !empty($form_data['comments']) ? $form_data['comments'] : null,
            'status_by' => get_current_user_id(),
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

        /**
         * Send Email
         */

        $send_mail = true;

        $to_emails = [$form_data['email'], $form_data['submit_email']];

        $email_data = [
            'name' => $form_data['name'],
            'email' => $to_emails,
        ];

        if (isset($form_data['additional_msg']) && !empty($form_data['additional_msg'])) {
            $email_data['additional_msg'] = $form_data['additional_msg'];
        }

        switch ($form_data['actionValue']) {
            case 'waiting':
                $email_data['subject'] = 'Your refund request is in the Queue';
                $email_data['email_templates'] = 'refund-waiting.html';
                $email_data['icon'] = 'refund-waiting.png';
                break;
            case 'declined':
                $email_data['subject'] = 'Opps!! Unfortunately it can\'t be refunded this time 😪';
                $email_data['email_templates'] = 'refund-declined.html';
                $email_data['icon'] = 'refund-declined.png';
                break;
            case 'approved':
                $email_data['subject'] = 'It feels much bad to see you Leave!!';
                $email_data['email_templates'] = 'refund-approved.html';
                $email_data['icon'] = 'refund-approved.png';
                break;

            default:
                $send_mail = false;
                break;
        }

        if ($send_mail) {
            $this->send_email_automation($email_data);
        }
        wp_die();
    }

    /**
     * Automation Email Send for Global Apps
     */

    public function send_email_automation($data)
    {
        $to = $data['email'];
        $subject = $data['subject'];
        $icon = isset($data['icon']) ? $data['icon'] : 'information.png';
        $additional_msg = isset($data['additional_msg'])&!empty($data['additional_msg']) ? $data['additional_msg'] : ' ';

        $swap_var = array(
            "{userName}" => $data['name'],
            "{subject}" => $subject,
            "{additionalMsg}" => $additional_msg,
            "{year}" => date('Y'),
            "{logoURL}" => BDT_REFUND_SYSTEM_URL . '/wp-content/plugins/assets/imgs/bdthemes-logo.jpg',
            "{iconURL}" => BDT_REFUND_SYSTEM_URL . 'wp-content/includes/email-templates/icons/' . $icon,
            "{emailAssetsURL}" => BDT_REFUND_SYSTEM_URL . 'wp-content/includes/email-templates/icons',
            "{appPrivacyPolicyURL}" => 'https://bdthemes.com/privacy-policy/',
            "{appTermsAndConditionURL}" => 'https://bdthemes.com/terms-of-use/',
            "{appSupportURL}" => 'https://bdthemes.com/support/',
        );

        ob_start();

        include BDT_REFUND_SYSTEM__PATH . '/includes/email-templates/' . $data['email_templates'];
        $email_content = ob_get_contents();

        foreach (array_keys($swap_var) as $key) {
            if (
                strlen($key) > 2 && trim($swap_var[$key]) != ''
            ) {
                $email_content = str_replace('$' . $key, $swap_var[$key], $email_content);
            }

        }

        ob_end_clean();

        // SET HTML CONTENT TYPE
        $headers = array('Content-Type: text/html; charset=UTF-8');
        // SEND WITH WP_MAIL() FUNCTION
        wp_mail($to, $subject, $email_content, $headers);
    }

    /**
     * Send Notification to discord
     */

    public function send_discord_notification($data)
    {
        if (false == $this->DISCORD_WEBHOOK) {
            return;
        }

        $webhookurl = $this->DISCORD_WEBHOOK;
        $timestamp = date('c', strtotime('now'));

        $json_data = json_encode([
            // Message
            'content' => "Hello @selimmw! We have received a refund request. Please @support team, can you provide more information about the refund?\n\n Client Name - " . $data['name'] . "\n Submit Email - " . $data['submit_email'] . "\n Client Email - " . $data['email'] . "\n License Code - " . $data['license'] . "\n Product Name - " . $data['product_name'] . "\n Refund Reason - " . $data['refund_reason'],

            // Username
            'username' => "Refund Bot",

            // Text-to-speech
            'tts' => false,

            // Embeds Array
            'embeds' => [
                [
                    // Embed Title
                    'title' => 'Refund request submitted',

                    // Embed Type
                    'type' => 'rich',

                    // Timestamp of embed must be formatted as ISO8601
                    'timestamp' => $timestamp,

                    // Embed left border color in HEX
                    'color' => hexdec('3366ff'),
                ],
            ],

        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $ch = curl_init($webhookurl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);
        // If you need to debug, or find out why you can't send message uncomment line below, and execute script.
        // echo $response;
        curl_close($ch);

    }
}

/**
 * Save Settings
 *
 */
function bdt_rs_save_settings()
{
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    $bdt_rs_app->save_settings($_POST);
}

/**
 * Get Refunds
 *
 */
function bdt_rs_get_refunds()
{
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    return $bdt_rs_app->get_refunds($_POST);
}

/**
 * Get Counts Refund
 *
 */
function bdt_rs_get_refunds_count()
{
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    return $bdt_rs_app->get_refunds_count($_POST);
}

/**
 * Get License Info
 *
 */
function bdt_rs_get_info()
{
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    $bdt_rs_app->detect_info($_POST);
}

/**
 * Update Action Trigger
 *
 */
function bdt_rs_action_trigger()
{
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    $bdt_rs_app->action_trigger($_POST);
}

/**
 * Submit Form
 *
 */
function bdt_rs_form()
{
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    return $bdt_rs_app->insert_refund($_POST);
}

/**
 * Notify Discord
 */
function bdt_rs_discord_notify()
{
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    return $bdt_rs_app->send_discord_notification($_POST);
}

/**
 * Delete Refund
 */
function bdt_rs_delete_refund_request($id)
{
    $bdt_rs_app = new BDT_REFUND_SYSTEM_APP();
    return $bdt_rs_app->delete_refund($id);
}
