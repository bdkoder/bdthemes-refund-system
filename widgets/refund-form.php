<?php

namespace Bdthemes\RefundSystem\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

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

		$this->add_control(
			'text_license',
			[
				'label'   => esc_html__('License', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Your License Key',
				'rows'    => 2,
			]
		);

		$this->add_control(
			'text_license_placeholder',
			[
				'label'   => esc_html__('License Placeholder', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Your License Key',
				'rows'    => 2,
			]
		);

		$this->add_control(
			'text_name',
			[
				'label'   => esc_html__('Name', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Your Name',
				'rows'    => 2,
			]
		);

		$this->add_control(
			'text_name_placeholder',
			[
				'label'   => esc_html__('Name Placeholder', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Your Name',
				'rows'    => 2,
			]
		);

		$this->add_control(
			'text_email',
			[
				'label'   => esc_html__('Email', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Your Email',
				'rows'    => 2,
			]
		);

		$this->add_control(
			'text_email_placeholder',
			[
				'label'   => esc_html__('Email Placeholder', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Your Email',
				'rows'    => 2,
			]
		);

		$this->add_control(
			'text_refund_reason',
			[
				'label'   => esc_html__('Refund Reason', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Refund Reason',
				'rows'    => 2,
			]
		);

		$this->add_control(
			'text_refund_reason_placeholder',
			[
				'label'   => esc_html__('Refund Reason Placeholder', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
			]
		);

		$this->add_control(
			'text_btn',
			[
				'label'   => esc_html__('Submit Button', 'bdt-refund-system'),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Submit',
				'rows'    => 2,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_common',
			[
				'label' => __('Common', 'bdt-refund-system'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'container_width',
			[
				'label' => __('Max Width', 'bdt-refund-system'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 500,
						'max' => 1200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-rs-form' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_spacing',
			[
				'label' => __('Space Between', 'bdt-refund-system'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-rs-form .bdt-margin' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_label',
			[
				'label' => __('Label', 'bdt-refund-system'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label' => __('Label Spacing', 'bdt-refund-system'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-rs-form .bdt-form-controls, {{WRAPPER}} .bdt-rs-form .bdt-textarea' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => __('Color', 'bdt-refund-system'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-form-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .bdt-form-label',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_button',
			[
				'label' => __('Button', 'bdt-refund-system'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_full_width',
			[
				'label'   => __('Button Full Width', 'bdthemes-element-pack'),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'button_color',
			[
				'label'     => __('Color', 'bdt-refund-system'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background',
				'selector'  => '{{WRAPPER}} .bdt-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .bdt-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __('Padding', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-button',
			]
		);

		$this->add_responsive_control(
			'button_radius',
			[
				'label'      => __('Border Radius', 'bdthemes-element-pack'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .bdt-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);


		$this->add_control(
			'button_heading',
			[
				'label'     => esc_html__('Hover Style', 'textdomain'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_color_hover',
			[
				'label'     => __('Color', 'bdt-refund-system'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background_hover',
				'selector'  => '{{WRAPPER}} .bdt-button:hover',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

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
					<label class="bdt-form-label" for="bdt-rs-license-key">
						<?php echo wp_kses_post( $settings['text_license'] ); ?>
					</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-license-key" name="product_license" type="text" placeholder="<?php echo esc_html( $settings['text_license_placeholder'] ); ?>" required>
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-2@s">
					<label class="bdt-form-label" for="bdt-rs-name">
						<?php echo wp_kses_post( $settings['text_name'] ); ?>
					</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-name" name="name" type="text" placeholder="<?php echo esc_html( $settings['text_name_placeholder'] ); ?>" required>
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-2@s">
					<label class="bdt-form-label" for="bdt-rs-email">
						<?php echo wp_kses_post( $settings['text_email'] ); ?>
					</label>
					<div class="bdt-form-controls">
						<input class="bdt-input" id="bdt-rs-email" name="email" type="email" placeholder="<?php echo esc_html( $settings['text_email_placeholder'] ); ?>" required>
					</div>
				</div>
				<div class="bdt-margin bdt-width-1-1">
					<label class="bdt-form-label" for="bdt-rs-message">
						<?php echo wp_kses_post( $settings['text_refund_reason'] ); ?>
					</label>
					<textarea class="bdt-textarea" rows="6" id="bdt-rs-message" name="message" placeholder="<?php echo esc_html( $settings['text_refund_reason_placeholder'] ); ?>"></textarea>
				</div>
				<input type="hidden" name="action" value="bdt_rs_form">
				<?php wp_nonce_field('bdt-rs-form-submit'); ?>

				<div class="bdt-margin <?php echo ('yes' == $settings['button_full_width']) ? ' bdt-width-1-1' : ''; ?>">
					<button class="bdt-button bdt-button-primary <?php echo ('yes' == $settings['button_full_width']) ? ' bdt-width-1-1' : ''; ?>" type="submit"><?php echo esc_html( $settings['text_btn'] ); ?></button>
				</div>
			</form>
		</div>
<?php
	}
}
