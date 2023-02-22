<?php

namespace Bdthemes\RefundSystem\Api;

class Request_List_Public {

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action('rest_api_init', array($this, 'manage_rest_api'));
    }

    ## https://www.sitepoint.com/creating-custom-endpoints-for-the-wordpress-rest-api/
    public function manage_rest_api() {

        register_rest_route('refund-request/v1', 'data', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_request_data')
        ));
    }

    public function get_request_data($request) {

        $data = array();
        $fetch_data = [];

        if (!function_exists('bdt_rs_get_refunds')) {
            $response = new \WP_REST_Response(['no-data']);
            $response->set_status(200);
            return $response;
        }

        $refunds_data = bdt_rs_get_refunds();
        $refunds_data = json_decode(json_encode($refunds_data), true);

        if (count($refunds_data) > 0) {

            foreach ($refunds_data as $_data) {
                $fetch_data[$_data['id']]['product_name']    = $_data['product_name'];
                $fetch_data[$_data['id']]['product_license'] = md5($_data['product_license']);
                $fetch_data[$_data['id']]['name']            = $_data['name'];
                $fetch_data[$_data['id']]['email']           = $_data['email'];
                $fetch_data[$_data['id']]['message']         = $_data['message'];
                $fetch_data[$_data['id']]['comments']        = $_data['comments'];
                $fetch_data[$_data['id']]['status']          = $_data['status'];
                $fetch_data[$_data['id']]['created_at']      = $_data['created_at'];
            }
            $data[] = $fetch_data;
        }

        $response = new \WP_REST_Response($data);
        $response->set_status(200);

        return $response;
    }
}
