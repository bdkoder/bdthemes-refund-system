<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('', 'bdthemes-refund-system')?></h1>

    <div class="bdts-wrapper">
        <div class="bdts-section bdt-flex bdt-padding-large">
            <div class="bdts-menu">
                <ul class="bdt-tab-left bdt-tab bdt-main-tab">
                    <li data-index="0">
                        <a href="#" class="bdt-margin-top bdt-link-heading">
                            <span class="dashicons dashicons-admin-generic"></span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bdt-switcher-wrapper">
                <ul id="bdt-tab-content" class="bdt-margin">
                    <li class="bdt-content-item bdt-active">
                        <?php if (current_user_can('administrator')): ?>
                            <form class="bdt-form-stacked bdt-margin-large" id="bdts-settings-form" method="POST">
                                <div class="bdt-margin">
                                    <label class="bdt-form-label" for="api_key">
                                        <?php echo __('API KEY', 'bdthemes-refund-system'); ?>
                                    </label>
                                    <div class="bdt-form-controls">
                                        <?php
$get_option = get_option('bdts_settings');
$get_option = $get_option;

$api_key = '';
if (isset($get_option['api_key'])) {
    $api_key = $get_option['api_key'];
}

$api_end_point = '';
if (isset($get_option['api_end_point'])) {
    $api_end_point = $get_option['api_end_point'];
}

?>
                                        <input class="bdt-input bdt-form-large" id="api_key" type="text" placeholder="API KEY" name="api_key" value="<?php echo esc_attr($api_key); ?>">
                                    </div>
                                </div>
                                <div class="bdt-margin">
                                    <label class="bdt-form-label" for="api_end_point">
                                        <?php echo __('API End Point', 'bdthemes-refund-system'); ?>
                                    </label>
                                    <div class="bdt-form-controls">
                                        <input class="bdt-input bdt-form-large" id="api_end_point" type="text" placeholder="API KEY" name="api_end_point" value="<?php echo esc_attr($api_end_point); ?>">
                                    </div>
                                </div>

                                                                <div class="bdt-margin">
                                                                    <label class="bdt-form-label" for="webhook">
                                                                        <?php echo __('Discord Webhook', 'bdthemes-refund-system'); ?>
                                                                    </label>
                                                                    <div class="bdt-form-controls">
                                                                        <?php
$get_option = get_option('bdts_settings');
$get_option = $get_option;

$webhook = '';
if (isset($get_option['webhook'])) {
    $webhook = $get_option['webhook'];
}

$api_end_point = '';
if (isset($get_option['api_end_point'])) {
    $api_end_point = $get_option['api_end_point'];
}

?>
                                                                        <input class="bdt-input bdt-form-large" id="webhook" type="text" placeholder="Webhook" name="webhook" value="<?php echo esc_attr($webhook); ?>">
                                                                    </div>
                                                                </div>
                                <input type="hidden" name="action" value="bdt_rs_save_settings">
                                <?php wp_nonce_field('save-settings');?>
                                <div class="bdt-margin">
                                    <button type="submit" class="bdt-w-100 bdt-button bdt-button-primary bdt-button-large" id="submit_event" name="submit_event">
                                        <?php echo __('SAVE SETTINGS', 'bdthemes-refund-system'); ?>
                                    </button>
                                </div>

                            </form>
                        <?php else: ?>
                            <div class="bdt-not-access">
                                <h3>Sorry, you are not able to access this area!</h3>
                                <span class="dashicons dashicons-lock"></span>
                            </div>
                        <?php endif;?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- This is the modal -->
    <div id="bdts-modal" data-bdt-modal data-bg-close="false">
        <div class="bdt-modal-dialog">
            <button class="bdt-modal-close-default" type="button" bdt-close></button>
            <div class="bdt-modal-header">
                <h2 class="bdt-modal-title">Details</h2>
            </div>
            <div class="bdt-modal-body" id="bdts-modal-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
            <div class="bdt-modal-footer bdt-text-right">
                <button class="bdt-button bdt-button-default bdt-modal-close" type="button">Close</button>
                <button class="bdt-button bdt-button-primary" type="button">Save</button>
            </div>
        </div>
    </div>

</div>