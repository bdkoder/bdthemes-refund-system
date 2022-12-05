<?php

namespace Bdthemes\RefundSystem\Widgets;

use Elementor\Widget_Base;

if (!defined('ABSPATH')) {
	exit;
}
// Exit if accessed directly

class Refund_Form extends Widget_Base {

	public function get_name() {
		return 'bdt-rs-form';
	}

	public function get_title() {
		return __('Refund Form', 'bdt-refund-system');
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return ['bdt-refund-system'];
	}

	public function get_style_depends() {
		return ['bdt-rs-form'];
	}

	public function get_script_depends() {
		return ['sweetalert2', 'bdt-rs-form'];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Content', 'bdt-refund-system'),
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$id = 'bdt-rs-form-' . $this->get_id();

		$this->add_render_attribute('form', [
			'class'         => 'bdt-rs-form',
			'id'            => $id,
			'data-settings' => wp_json_encode([
				'id' => '#' . $id
			])
		]);

?>
		<div <?php $this->print_render_attribute_string('form'); ?>>
			<form class="bdt-grid-small" id="bdt-rs-form" method="post" bdt-grid>
				<div class="bdt-margin bdt-width-1-1">
					<label class="bdt-form-label" for="bdt-rs-license-key">Your License Key</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-license-key" name="product_license" type="text" placeholder="Your License Key" required value="72A32394-65146F0C-5B665ADA-602E1308">
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-2@s">
					<label class="bdt-form-label" for="bdt-rs-name">Your Name</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-name" name="name" type="text" placeholder="Your Name" required value="XXX">
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-2@s">
					<label class="bdt-form-label" for="bdt-rs-email">Your Email</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-email" name="email" type="text" placeholder="Your Email" required value="XXX">
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-1">
					<label class="bdt-form-label" for="bdt-rs-message">Refund Reason</label>
					<textarea class="uk-textarea" rows="6" id="bdt-rs-message" name="message"></textarea>
				</div>
				<input type="hidden" name="action" value="bdt_rs_form">
				<?php wp_nonce_field('bdt-rs-form-submit'); ?>

				<div class="bdt-margin bdt-width-1-1">
					<button class="bdt-button bdt-button-primary bdt-width-1-1" type="submit">Submit</button>
				</div>
			</form>
		</div>
<?php
	}
}
