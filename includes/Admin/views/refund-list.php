<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Refund List', 'bdthemes-refund-system') ?></h1>

    <?php if (isset($_GET['event-deleted']) && $_GET['event-deleted'] == true) : ?>
        <div class="notice notice-success">
            <p><?php _e('Event has been deleted successfully!', 'bdthemes-refund-system'); ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <?php

        $table = new \Bdthemes\RefundSystem\Admin\Refund_List();
        $table->prepare_items();
        $table->display();

        ?>
    </form>


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
            <?php wp_nonce_field(-1, 'bdt_rs_action_nonce'); ?>
            <div class="bdt-modal-footer bdt-text-right">
                <button class="bdt-button bdt-button-default bdt-modal-close" type="button">Close</button>
                <!-- <button class="bdt-button bdt-button-primary" type="button">Save</button> -->
            </div>
        </div>
    </div>

</div>