<?php

namespace NasAcademy\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly


class Event_Form extends Widget_Base
{
 
	public function get_name()
	{
		return 'nas-event-form';
	}

	public function get_title()
	{
		return __('Event Form', 'nas-drawing');
	}

	public function get_icon()
	{
		return 'eicon-posts-ticker';
	}

	public function get_categories()
	{
		return ['nas-academy'];
	}

	public function get_script_depends() {
		return [ 'sweetalert2', 'nas-mask-phone', 'nas-event-form' ];
	}

	protected function _register_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Content', 'nas-drawing'),
			]
		);

		$this->add_control(
            'events',
            [
                'label'   => __('Select Events', 'nas-drawing'),
                'type'    => Controls_Manager::SELECT,
                'options' => wd_frontend_fetch_event(),
                'default' => 'empty',
            ]
        );

		$this->add_control(
			'show_labels',
			[
				'label'   => esc_html__( 'Label', 'nas-drawing' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'custom_text',
			[
				'label'     => esc_html__( 'Custom Text / Placeholder', 'nas-drawing' ),
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'categories_heading',
			[
				'label'     => esc_html__( 'Categories Field', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'categories_label',
			[
				'label'       => esc_html__( 'Label', 'nas-drawing' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Select Category*', 'nas-drawing' ),
				'condition'   => [
					'custom_text' => 'yes',
				],
			]
		);		
		
		
		$this->add_control(
			'email_heading',
			[
				'label'     => esc_html__( 'Email Field', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'email_label',
			[
				'label'       => esc_html__( 'Label', 'nas-drawing' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Email', 'nas-drawing' ),
				'condition'   => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'guardian_name_heading',
			[
				'label'     => esc_html__( 'Guardian Name Field', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'guardian_name_label',
			[
				'label'       => esc_html__( 'Label', 'nas-drawing' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Guardian Name*', 'nas-drawing' ),
				'condition'   => [
					'custom_text' => 'yes',
				],
			]
		);			
		
		$this->add_control(
			'name_heading',
			[
				'label'     => esc_html__( 'Name Field', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'name_label',
			[
				'label'       => esc_html__( 'Label', 'nas-drawing' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Name*', 'nas-drawing' ),
				'condition'   => [
					'custom_text' => 'yes',
				],
			]
		);	
		
		$this->add_control(
			'phone_heading',
			[
				'label'     => esc_html__( 'Phone Number Field', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'phone_label',
			[
				'label'       => esc_html__( 'Label', 'nas-drawing' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Phone Number*', 'nas-drawing' ),
				'condition'   => [
					'custom_text' => 'yes',
				],
			]
		);		
		
		
		$this->add_control(
			'address_heading',
			[
				'label'     => esc_html__( 'Address Field', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'address_label',
			[
				'label'       => esc_html__( 'Label', 'nas-drawing' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Your Address*', 'nas-drawing' ),
				'condition'   => [
					'custom_text' => 'yes',
				],
			]
		);	

		$this->add_control(
			'file_heading',
			[
				'label'     => esc_html__( 'File Field', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'file_label',
			[
				'label'       => esc_html__( 'Label', 'nas-drawing' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Your File*', 'nas-drawing' ),
				'condition'   => [
					'custom_text' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_file_button',
			[
				'label' => esc_html__( 'File Button', 'nas-drawing' ),
			]
		);

		$this->add_control(
            'file_button_icon',
            [
                'label'            => __('Icon', 'nas-drawing'),
                'type'             => Controls_Manager::ICONS,
            ]
        );

		
		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Submit Form', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'   => esc_html__( 'Size', 'nas-drawing' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''           => esc_html__( 'Default', 'nas-drawing' ),
					'small'      => esc_html__( 'Small', 'nas-drawing' ),
					'large'      => esc_html__( 'Large', 'nas-drawing' ),
					'full-width' => esc_html__( 'Full Width', 'nas-drawing' ),
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'   => esc_html__( 'Alignment', 'nas-drawing' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => '',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'nas-drawing' ),
						'icon'  => 'fas fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'nas-drawing' ),
						'icon'  => 'fas fa-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'nas-drawing' ),
						'icon'  => 'fas fa-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Justified', 'nas-drawing' ),
						'icon'  => 'fas fa-align-justify',
					],
				],
				'prefix_class' => 'elementor%s-button-align-',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_settings',
			[
				'label' => esc_html__( 'Additional Settings', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'on_success_redirect_url',
			[
				'label'   => esc_html__( 'Redirect On Success', 'nas-drawing' ),
				'type'    => Controls_Manager::URL,
				'description' => esc_html__( 'Please add redirect URL to redirect on success state.', 'nas-drawing' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_notify_content',
			[
				'label' => esc_html__( 'Notify Content', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'error_title',
			[
				'label'   => esc_html__( 'Error Title', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Error!', 'nas-drawing' ),
			]
		);		
		
		$this->add_control(
			'success_title',
			[
				'label'   => esc_html__( 'Success Title', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Success!', 'nas-drawing' ),
			]
		);		
		
		$this->add_control(
			'img_size_invalid',
			[
				'label'   => esc_html__( 'Image Size Invalid', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Sorry Image should not more than 2MB', 'nas-drawing' ),
			]
		);		
		
		$this->add_control(
			'img_invalid',
			[
				'label'   => esc_html__( 'Image Invalid', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Sorry, your selected file not a image', 'nas-drawing' ),
			]
		);		
		
		$this->add_control(
			'fields_empty',
			[
				'label'   => esc_html__( 'Fields Empty', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Fields cannot be blank', 'nas-drawing' ),
			]
		);
		
		$this->add_control(
			'img_empty',
			[
				'label'   => esc_html__( 'Image Empty', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Image Empty', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'image_failed_upload',
			[
				'label'   => esc_html__( 'Image Upload Failed', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Image Failed to Upload', 'nas-drawing' ),
			]
		);


		$this->add_control(
			'submit_success',
			[
				'label'   => esc_html__( 'Submit Success', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Your form submitted successfully', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'submit_success_sms',
			[
				'label'   => esc_html__( 'Submit Success SMS', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Form submitted successfully of {{NAME}}. NAS Academy, ', 'nas-drawing' ),
				'description' => '{{NAME}} -  For Participator Name'
			]
		);

		$this->add_control(
			'otp_heading',
			[
				'label'     => esc_html__( 'OTP Fields', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'otp_sms',
			[
				'label'   => esc_html__( 'OTP SMS', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Your OTP is {{OTP}} NAS Drawing Academy', 'nas-drawing' ),
				'description' => '{{OTP}} -  Must use for OTP code'
			]
		);
		
		$this->add_control(
			'otp_title',
			[
				'label'   => esc_html__( 'OTP Alert Title', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Input OTP Code', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'otp_input_label',
			[
				'label'   => esc_html__( 'OTP Input Label', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'You may have to wait upto 5 min to receive your code', 'nas-drawing' ),
			]
		);		
		
		$this->add_control(
			'otp_input_placeholder',
			[
				'label'   => esc_html__( 'OTP Input Placeholder', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Enter your OTP code', 'nas-drawing' ),
			]
		);		
		
		$this->add_control(
			'otp_wrong',
			[
				'label'   => esc_html__( 'OTP Wrong', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Your OTP is not correct! Please try again', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'loading_heading',
			[
				'label'     => esc_html__( 'Loading Fields', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'loading_title',
			[
				'label'   => esc_html__( 'Title', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Uploading...', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'loading_text',
			[
				'label'   => esc_html__( 'Text', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Please wait...', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'internet_issue',
			[
				'label'   => esc_html__( 'Internet Issue', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Send Failed, Internet Problem, please try agian.', 'nas-drawing' ),
			]
		);		
		
		$this->add_control(
			'phone_error',
			[
				'label'   => esc_html__( 'Phone Number Error', 'nas-drawing' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Your phone number is not correct.', 'nas-drawing' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Form Style', 'nas-drawing' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'   => esc_html__( 'Field Space', 'nas-drawing' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => '15',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-field-group:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};margin-top: 0;',
				],
			]
		);

		$this->add_control(
			'field_height',
			[
				'label' => esc_html__( 'Field Height', 'nas-drawing' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-field-group .bdt-input, .bdt-select:not([multiple]):not([size])' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_labels',
			[
				'label'     => esc_html__( 'Label', 'nas-drawing' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => esc_html__( 'Spacing', 'nas-drawing' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-field-group > label' => 'margin-bottom: {{SIZE}}{{UNIT}}; display: block;',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label'     => esc_html__( 'Text Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-form-label' => 'color: {{VALUE}};',
				],
				// 'scheme' => [
				// 	'type'  => Schemes\Color::get_type(),
				// 	'value' => Schemes\Color::COLOR_3,
				// ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .bdt-form-label',
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => esc_html__( 'Fields', 'nas-drawing' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_field_style' );

		$this->start_controls_tab(
			'tab_field_normal',
			[
				'label' => esc_html__( 'Normal', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-field-group .bdt-input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-field-group textarea'   => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_placeholder_color',
			[
				'label'     => esc_html__( 'Placeholder Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-field-group .bdt-input::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bdt-field-group textarea::placeholder'   => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-field-group .bdt-input' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-field-group textarea'   => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'field_border',
				'label'       => esc_html__( 'Border', 'nas-drawing' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-field-group .bdt-input, {{WRAPPER}} .bdt-field-group textarea',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'nas-drawing' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-field-group .bdt-input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bdt-field-group textarea'   => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'field_box_shadow',
				'selector' => '{{WRAPPER}} .bdt-field-group .bdt-input, {{WRAPPER}} .bdt-field-group textarea',
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label'      => esc_html__( 'Padding', 'nas-drawing' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-field-group .bdt-input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
					'{{WRAPPER}} .bdt-field-group textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'field_typography',
				'label'     => esc_html__( 'Typography', 'nas-drawing' ),
				//'scheme'    => Schemes\Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .bdt-field-group .bdt-input, {{WRAPPER}} .bdt-field-group textarea',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_focus',
			[
				'label' => esc_html__( 'Focus', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'field_focus_background',
			[
				'label'     => esc_html__( 'Background', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-field-group .bdt-input:focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-field-group textarea:focus'   => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_focus_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-field-group .bdt-input:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .bdt-field-group textarea:focus'   => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'field_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->start_controls_section(
			'section_file_button_style',
			[
				'label' => esc_html__( 'File Button', 'nas-drawing' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'file_button_height',
			[
				'label' => esc_html__( 'Field Height', 'nas-drawing' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-input-file-btn' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .nas-img-preview-area' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'file_button_width',
			[
				'label' => esc_html__( 'Field Width (%)', 'nas-drawing' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bdt-file-upload-group .bdt-form-custom' => 'width: {{SIZE}}% !important;',
					'{{WRAPPER}} .bdt-file-upload-group .bdt-form-custom .bdt-input-file-btn' => 'width: {{SIZE}}% !important;',
				],
			]
		);

		$this->add_control(
			'file_button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'file_button_typography',
				'selector' => '{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'file_button_background_color',
				'selector'  => '{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'file_button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn',
			]
		);

		$this->add_control(
			'file_button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'nas-drawing' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'file_button_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'nas-drawing' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'file_button_icon_heading',
			[
				'label'     => esc_html__( 'Icon Customize', 'nas-drawing' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'file_button_icon_typography',
				'selector' => '{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn .nas-file-button-icon',
			]
		);

		$this->add_control(
			'file_button_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn .nas-file-button-icon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'file_button_icon_background',
				'selector'  => '{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn .nas-file-button-icon'
			]
		);

		$this->add_responsive_control(
			'file_button_icon_padding',
			[
				'label'      => esc_html__( 'Padding', 'nas-drawing' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn .nas-file-button-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'file_button_icon_border',
				'label'       => esc_html__( 'Border', 'nas-drawing' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn .nas-file-button-icon', 
			]
		);

		$this->add_control(
			'file_button_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'nas-drawing' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-form-custom .bdt-input-file-btn .nas-file-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_submit_button_style',
			[
				'label' => esc_html__( 'Submit Button', 'nas-drawing' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .bdt-button',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background_color',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .bdt-button'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name'        => 'button_border',
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .bdt-button',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'nas-drawing' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_text_padding',
			[
				'label'      => esc_html__( 'Padding', 'nas-drawing' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .bdt-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'nas-drawing' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bdt-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'button_background_hover_color',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .bdt-button:hover'
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'nas-drawing' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .bdt-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Animation', 'nas-drawing' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();
		/**
		 * Fetch Table fo Categories
		 * 
		 */
		global $wpdb;
		$table_name = $wpdb->prefix.'nas_categories';
		 
		$event_id = $settings['events'];
		
		$categories = $wpdb->get_results( "SELECT * FROM $table_name WHERE EVENT = '$event_id';"); 

		$this->add_render_attribute( 'event-form', 'class', ['event-form'] );
		$this->add_render_attribute( 'event-form', 'bdt-grid', '' );
		$this->add_render_attribute( 'event-form', 'action', site_url() . '/wp-admin/admin-ajax.php' );
		$this->add_render_attribute( 'event-form', 'method', 'post' );

	 
			$this->add_render_attribute( 'button', 'class', [
				'elementor-button bdt-button bdt-button-primary bdt-event-submit-button',
				'bdt-button-' . $settings['button_size']
			] );
	

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute(
            [
                'event-form' => [
					'class' => 'event-form-wrapper',
                    'data-settings' => [
                        wp_json_encode([
                            'id'                => 'event-form-' . $this->get_id(),
                            'errorTitle'  => $settings['error_title'], 
                            'successTitle'  => $settings['success_title'],
                            'imgSizeInvalid'  => $settings['img_size_invalid'],
                            'imgInvalid'  => $settings['img_invalid'],
                            'fieldsEmpty'  => $settings['fields_empty'],
                            'imgEmpty'  => $settings['img_empty'],
                            'imageFailedUpload'  => $settings['image_failed_upload'],
                            'submitSuccess'  => $settings['submit_success'],
                            'submitSuccessSMS'  => $settings['submit_success_sms'],
                            'otpSMS'  => $settings['otp_sms'],
                            'otpTitle'  => $settings['otp_title'],
                            'otpInputLabel'  => $settings['otp_input_label'],
                            'otpInputPlaceholder'  => $settings['otp_input_placeholder'],
                            'otpWrong'  => $settings['otp_wrong'],
                            'loadingTitle'  => $settings['loading_title'],
                            'loadingText'  => $settings['loading_text'],
                            'internetIssue'  => $settings['internet_issue'],
                            'phoneError'  => $settings['phone_error'],
                            'successRedirectURL'  => $settings['on_success_redirect_url']['url'],
                        ]),
                    ],
                ],
            ]
        );
 
		
 
		?>
		<div <?php echo $this->get_render_attribute_string('event-form'); ?>>
				<form <?php echo $this->get_render_attribute_string( 'event-form' ); ?> enctype="multipart/form-data">



					<input type="hidden" name="event" value="<?php echo $settings['events'] ?>" id="event">
					 

					<div class="bdt-field-group elementor-field-required bdt-width-1-1 bdt-first-column">
					<?php
						if ( $settings['show_labels'] ) {
							if ( 'yes' == $settings['custom_text'] ) {
								echo '<label for="category" class="bdt-form-label">' . $settings['categories_label'] . '</label>';
							} else {
								echo '<label for="category" class="bdt-form-label">' . esc_html__( 'Select Category*', 'nas-academy' ) . '</label>';
							}
						}
						$category_label = ($settings['custom_text'] == 'yes') ? $settings['categories_label'] : esc_html__( 'Select Category', 'nas-academy' );
						?>
						<div class="bdt-form-controls">
							<select class="bdt-select bdt-form-default  bdt-input" id="category" name="category">
								<option value=""><?php echo esc_html($category_label); ?></option>
								<?php
								if(!empty($categories)){    
									foreach($categories as $row){   
										echo '<option value="'.$row->id.'">'.$row->name.'</option>';
									}
								}
								?>
							</select>
						</div>
					</div>

					
					<div class="bdt-field-group elementor-field-required bdt-width-1-1 bdt-first-column">
					<?php
						if ( $settings['show_labels'] ) {
							if ( 'yes' == $settings['custom_text'] ) {
								echo '<label for="name" class="bdt-form-label">' . $settings['name_label'] . '</label>';
							} else {
								echo '<label for="name" class="bdt-form-label">' . esc_html__( 'Name*', 'nas-academy' ) . '</label>';
							}
						}
						$name_label = ($settings['custom_text'] == 'yes') ? $settings['name_label'] : esc_html__( 'Name', 'nas-academy' );
						?>
						<div class="bdt-form-controls">
							<input type="text" name="name" id="name" 
							placeholder="<?php echo esc_attr($name_label); ?>" class="bdt-input bdt-form-default" required="">
						</div>					
					</div>

					
					<div class="bdt-field-group elementor-field-required bdt-width-1-1 bdt-first-column">
					<?php
						if ( $settings['show_labels'] ) {
							if ( 'yes' == $settings['custom_text'] ) {
								echo '<label for="guardian-name" class="bdt-form-label">' . $settings['guardian_name_label'] . '</label>';
							} else {
								echo '<label for="guardian-name" class="bdt-form-label">' . esc_html__( 'Guardian Name*', 'nas-academy' ) . '</label>';
							}
						}
						$guardian_name_label = ($settings['custom_text'] == 'yes') ? $settings['guardian_name_label'] : esc_html__( 'Guardian Name', 'nas-academy' );
						?>
						<div class="bdt-form-controls">
							<input type="text" name="guardianName" id="guardian-name" 
							placeholder="<?php echo esc_attr($guardian_name_label); ?>" class="bdt-input bdt-form-default" required="">
						</div>					
					</div>

					
					<div class="bdt-field-group elementor-field-required bdt-width-1-1 bdt-first-column">
					<?php
						if ( $settings['show_labels'] ) {
							if ( 'yes' == $settings['custom_text'] ) {
								echo '<label for="phone" class="bdt-form-label">' . $settings['phone_label'] . '</label>';
							} else {
								echo '<label for="phone" class="bdt-form-label">' . esc_html__( 'Phone Number*', 'nas-academy' ) . '</label>';
							}
						}
						$phone_label = ($settings['custom_text'] == 'yes') ? $settings['phone_label'] : esc_html__( 'Phone Number', 'nas-academy' );
						?>
						<div class="bdt-form-controls">
							<input type="text" name="phone" id="phone" 
							placeholder="<?php echo esc_attr($phone_label); ?>" class="bdt-input bdt-form-default" required="">
						</div>					
					</div>

					<div class="bdt-field-group elementor-field-required bdt-width-1-1 bdt-first-column">
						
					<?php
						if ( $settings['show_labels'] ) {
							if ( 'yes' == $settings['custom_text'] ) {
								echo '<label for="email" class="bdt-form-label">' . $settings['email_label'] . '</label>';
							} else {
								echo '<label for="email" class="bdt-form-label">' . esc_html__( 'Email', 'nas-academy' ) . '</label>';
							}
						}
						$email_label = ($settings['custom_text'] == 'yes') ? $settings['email_label'] :  esc_html__( 'Email', 'nas-academy' );
						?>
						<div class="bdt-form-controls">
							<input type="text" name="email" id="email" 
							placeholder="<?php echo esc_attr($email_label); ?>" class="bdt-input bdt-form-default">
						</div>					
					</div>

					<div class="bdt-field-group bdt-width-1-1 elementor-field-required bdt-grid-margin">
					<?php
						if ( $settings['show_labels'] ) {
							if ( 'yes' == $settings['custom_text'] ) {
								echo '<label for="address" class="bdt-form-label">' . $settings['address_label'] . '</label>';
							} else {
								echo '<label for="address" class="bdt-form-label">' . esc_html__( 'Your Address*', 'nas-academy' ) . '</label>';
							}
						}
						$address_label = ($settings['custom_text'] == 'yes') ? $settings['address_label'] : esc_html__( 'Your Address', 'nas-academy' );
						?>
						<div class="bdt-form-controls">
							<textarea type="textarea" name="address" id="address" rows="5" 
							placeholder="<?php echo esc_attr($address_label); ?>" class="bdt-textarea bdt-form-default" required="" spellcheck="false"></textarea>
						</div> 
					</div>
					
					<div class="bdt-field-group bdt-file-upload-group bdt-width-1-1 elementor-field-required bdt-grid-margin">
						<div class="bdt-form-controls bdt-form-custom bdt-flex">
							<input type="file" name="file" id="file" class="" />
            				<button class="bdt-button bdt-button-default bdt-input-file-btn bdt-flex bdt-flex-middle" type="button">
							
							<span class="nas-file-button-icon bdt-margin-small-right" style="line-height:1;">
							<?php Icons_Manager::render_icon($settings['file_button_icon'], ['aria-hidden' => 'true', 'class' => 'fa-fw']); ?>
							</span>
							
							<?php 
								
								$file_label = ($settings['custom_text'] == 'yes') ? $settings['file_label'] : 'Click here to Select your file';
								echo esc_attr($file_label); 
							?>
							</button>
 
							
						</div> 
						
					</div>

					<img src="" id="blah" alt="">
 
					<input name="security" value="<?php echo wp_create_nonce("uploadingFile"); ?>" type="hidden">


					<div class="elementor-field-type-submit bdt-field-group bdt-flex bdt-width-1-1 bdt-first-column bdt-margin-remove-top">
						<button type="submit" name="submit" <?php echo $this->get_render_attribute_string('button'); ?>>
								<span><?php echo esc_html($settings['button_text']); ?></span>
						</button>
					</div>
				</form>
		</div>
	<?php }

	
}
