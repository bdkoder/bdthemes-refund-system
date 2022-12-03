<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Edit Event', 'nas-academy') ?></h1>
    
    <?php if( isset($_GET['event-updated']) ): ?>
        <div class="notice notice-success">
        <p><?php _e('Event has been updated successfully!', 'nas-academy'); ?></p>
        </div>
    <?php endif;?>
    
<?php //var_dump($address); ?>

    <form action="" method="post">
        <table class="form-table">
            <tr class="row<?php echo $this->has_error( 'name' ) ? ' form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="name"><?php _e('Name', 'nas-academy') ?></label>
                </th>
                <td>
                    <input type="text" name="name" id="name" class="regular-text" value="<?php echo esc_attr( $address->name ); ?>">
                    <?php if( $this->has_error( 'name' ) ): ?>
                    <p class="description error"><?php echo $this->get_error( 'name' ); ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr class="row<?php echo $this->has_error( 'date' ) ? ' form-invalid' : ''; ?>">
                <th scope="row">
                    <label for="date"><?php _e('Date', 'nas-academy') ?></label>
                </th>
                <td>
                    <input type="date" name="date" id="date" class="regular-text" value="<?php echo esc_attr( date('Y-m-d', strtotime($address->date)) ); ?>">
                     <?php if( $this->has_error( 'date' ) ): ?>
                    <p class="description error"><?php echo $this->get_error( 'date' ); ?></p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

         <input type="hidden" name="id" value="<?php echo esc_attr( $address->id ); ?>">

        <?php wp_nonce_field('new-event'); ?>
        <?php submit_button(__('Update Event', 'nas-academy'), 'primary', 'submit_event'); ?>

    </form>


</div>