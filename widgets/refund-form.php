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
			<form class="bdt-grid-small" bdt-grid>
				<div class="bdt-margin bdt-width-1-1">
					<label class="bdt-form-label" for="bdt-rs-license-key">Your License Key</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-license-key" type="text" placeholder="Your License Key">
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-2@s">
					<label class="bdt-form-label" for="bdt-rs-name">Your Name</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-name" type="text" placeholder="Your Name">
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-2@s">
					<label class="bdt-form-label" for="bdt-rs-email">Your Email</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-email" type="text" placeholder="Your Email">
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-2@s">
					<input class="bdt-input" type="text" placeholder="50" aria-label="50">
				</div>
				<div class="bdt-margin bdt-width-1-2@s">
					<input class="bdt-input" type="text" placeholder="50" aria-label="50">
				</div>
				<div class="bdt-margin bdt-width-1-1">
					<button class="bdt-button bdt-button-primary bdt-width-1-1">Submit</button>
				</div>
			</form>
		</div>
<?php
	}
}
