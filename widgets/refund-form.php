<?php

namespace Bdthemes\RefundSystem\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly


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
?>
		Form
<?php
	}
}
