<?php
namespace NasAcademy\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Icons_Manager;

if ( !defined('ABSPATH') )
    exit; // Exit if accessed directly

class Participator_List extends Widget_Base {

    public function get_name() {
        return 'nas-participator-list';
    }

    public function get_title() {
        return __('Participator List', 'nas-drawing');
    }

    public function get_icon() {
        return 'eicon-editor-list-ul';
    }

    public function get_categories() {
        return ['nas-academy'];
    }

    public function get_script_depends() {
        return ['datatables', 'nas-participator-list'];
    }

    public function get_style_depends() {
        return ['datatables', 'datatables-uikit', 'nas-participator-list'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'section_content', [
                'label' => __('Content', 'nas-drawing'),
            ]
        );

        $this->add_control(
            'events', [
                'label'   => __('Select Events', 'nas-drawing'),
                'type'    => Controls_Manager::SELECT,
                'options' => wd_frontend_fetch_event(),
                'default' => 'empty',
            ]
        );
        
        $this->add_control(
            'show_certificate',         [
                'label'        => __('Show Certificate', 'bdthemes-element-pack'),
                'type'         => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'table_responsive_control', [
                'label'     => __('Responsive', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'table_responsive_2',
                'options'   => [
                        'table_responsive_no' => esc_html__('No Responsive', 'bdthemes-element-pack'),
                        'table_responsive_1'  => esc_html__('Responsive 1', 'bdthemes-element-pack'),
                        'table_responsive_2'  => esc_html__('Responsive 2', 'bdthemes-element-pack'),
                        'horizontal_scroll'   => esc_html__('Horizontal Scroll', 'bdthemes-element-pack'),
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'certificate_link', [
                'label'       => __('Certificate Link', 'bdthemes-element-pack'),
                'type'        => Controls_Manager::URL,
                'dynamic'     => ['active' => true],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_table', [
                'label' => __('Table', 'nas-drawing'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'stripe_style', [
                'label' => __('Stripe Style', 'nas-drawing'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'table_border_style', [
                'label'     => __('Border Style', 'nas-drawing'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                        'none'   => __('None', 'nas-drawing'),
                        'solid'  => __('Solid', 'nas-drawing'),
                        'double' => __('Double', 'nas-drawing'),
                        'dotted' => __('Dotted', 'nas-drawing'),
                        'dashed' => __('Dashed', 'nas-drawing'),
                        'groove' => __('Groove', 'nas-drawing'),
                ],
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list table' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'table_border_width', [
                'label'     => __('Border Width', 'nas-drawing'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                        'size' => 1,
                ],
                'range'     => [
                        'px' => [
                                'min' => 0,
                                'max' => 4,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list table' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'table_border_color', [
                'label'     => __('Border Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ccc',
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list table' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_header', [
                'label' => __('Header', 'nas-drawing'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'header_background', [
                'label'     => __('Background', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e7ebef',
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list th' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_color', [
                'label'     => __('Text Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#333',
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list th' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_border_style', [
                'label'     => __('Border Style', 'nas-drawing'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                        'none'   => __('None', 'nas-drawing'),
                        'solid'  => __('Solid', 'nas-drawing'),
                        'double' => __('Double', 'nas-drawing'),
                        'dotted' => __('Dotted', 'nas-drawing'),
                        'dashed' => __('Dashed', 'nas-drawing'),
                        'groove' => __('Groove', 'nas-drawing'),
                ],
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list th' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'header_border_width', [
                'label'     => __('Border Width', 'nas-drawing'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                        'size' => 1,
                ],
                'range'     => [
                        'px' => [
                                'min' => 0,
                                'max' => 20,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list th' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'header_border_color', [
                'label'     => __('Border Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ccc',
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list th' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'header_padding', [
                'label'      => __('Padding', 'nas-drawing'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default'    => [
                        'top'    => 1,
                        'bottom' => 1,
                        'left'   => 1,
                        'right'  => 2,
                        'unit'   => 'em'
                ],
                'selectors'  => [
                        '{{WRAPPER}} .nas-participator-list th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'     => 'header_text_typography',
                'selector' => '{{WRAPPER}} .nas-participator-list th',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_body', [
                'label' => __('Body', 'nas-drawing'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'cell_border_style', [
                'label'     => __('Border Style', 'nas-drawing'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                        'none'   => __('None', 'nas-drawing'),
                        'solid'  => __('Solid', 'nas-drawing'),
                        'double' => __('Double', 'nas-drawing'),
                        'dotted' => __('Dotted', 'nas-drawing'),
                        'dashed' => __('Dashed', 'nas-drawing'),
                        'groove' => __('Groove', 'nas-drawing'),
                ],
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list td' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'cell_border_width', [
                'label'     => __('Border Width', 'nas-drawing'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                        'size' => 1,
                ],
                'range'     => [
                        'px' => [
                                'min' => 0,
                                'max' => 20,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list td' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'cell_padding', [
                'label'      => __('Cell Padding', 'nas-drawing'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default'    => [
                        'top'    => 0.5,
                        'bottom' => 0.5,
                        'left'   => 1,
                        'right'  => 1,
                        'unit'   => 'em'
                ],
                'selectors'  => [
                        '{{WRAPPER}} .nas-participator-list td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'     => 'body_text_typography',
                'selector' => '{{WRAPPER}} .nas-participator-list td',
            ]
        );

        $this->start_controls_tabs('tabs_body_style');

        $this->start_controls_tab(
            'tab_normal', [
                'label' => __('Normal', 'nas-drawing'),
            ]
        );

        $this->add_control(
            'normal_background', [
                'label'     => __('Background', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list td' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'normal_color', [
                'label'     => __('Text Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'normal_border_color', [
                'label'     => __('Border Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ccc',
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list td' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_hover', [
                'label' => __('Hover', 'nas-drawing'),
            ]
        );

        $this->add_control(
            'row_hover_background', [
                'label'     => __('Background', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list table tr:hover td' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'row_hover_text_color', [
                'label'     => __('Text Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list table tr:hover td' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_stripe', [
                'label'     => __('Stripe', 'nas-drawing'),
                'condition' => [
                        'stripe_style' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stripe_background', [
                'label'     => __('Background', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#f5f5f5',
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list tr:nth-child(even) td' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                        'stripe_style' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'stripe_color', [
                'label'     => __('Text Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list tr:nth-child(even) td' => 'color: {{VALUE}};',
                ],
                'condition' => [
                        'stripe_style' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_leading_column', [
                'label'     => __('Leading Column', 'nas-drawing'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                        'leading_column_show' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'leading_column_border_style', [
                'label'     => __('Border Style', 'nas-drawing'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'solid',
                'options'   => [
                        'none'   => __('None', 'nas-drawing'),
                        'solid'  => __('Solid', 'nas-drawing'),
                        'double' => __('Double', 'nas-drawing'),
                        'dotted' => __('Dotted', 'nas-drawing'),
                        'dashed' => __('Dashed', 'nas-drawing'),
                        'groove' => __('Groove', 'nas-drawing'),
                ],
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list tr td:nth-child(1)' => 'border-style: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'leading_column_border_width', [
                'label'     => __('Border Width', 'nas-drawing'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                        'size' => 1,
                ],
                'range'     => [
                        'px' => [
                                'min' => 0,
                                'max' => 20,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list tr td:nth-child(1)' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'leading_column_padding', [
                'label'      => __('Cell Padding', 'nas-drawing'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default'    => [
                        'top'    => 0.5,
                        'bottom' => 0.5,
                        'left'   => 1,
                        'right'  => 1,
                        'unit'   => 'em'
                ],
                'selectors'  => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list tr td:nth-child(1)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(), [
                'name'     => 'leading_column_text_typography',
                'selector' => '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list tr td:nth-child(1)',
            ]
        );

        $this->start_controls_tabs('tabs_leading_column_normal_style');

        $this->start_controls_tab(
            'leading_column_tab_normal', [
                'label' => __('Normal', 'nas-drawing'),
            ]
        );

        $this->add_control(
            'leading_column_normal_background', [
                'label'     => __('Background', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list tr td:nth-child(1)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'leading_column_normal_color', [
                'label'     => __('Text Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list tr td:nth-child(1)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'leading_column_normal_border_color', [
                'label'     => __('Border Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#ccc',
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list tr td:nth-child(1)' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'leading_column_tab_hover', [
                'label' => __('Hover', 'nas-drawing'),
            ]
        );

        $this->add_control(
            'leading_column_row_hover_background', [
                'label'     => __('Background', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list table tr:hover > td:nth-child(1)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'leading_column_row_hover_text_color', [
                'label'     => __('Text Color', 'nas-drawing'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list table tr:hover > td:nth-child(1)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_filter_style', [
                'label' => esc_html__('Filter', 'bdthemes-element-pack'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('filter_style');

        $this->start_controls_tab(
            'filter_header_style', [
                'label' => __('Header', 'bdthemes-element-pack'),
            ]
        );

        $this->add_control(
            'datatable_header_text_color', [
                'label'     => esc_html__('Text Color', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list .dataTables_length label, {{WRAPPER}} .nas-participator-list .dataTables_filter label' => 'color: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );


        $this->add_control(
            'datatable_header_input_color', [
                'label'     => esc_html__('Input Color', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list .dataTables_filter input, {{WRAPPER}} .nas-participator-list .dataTables_length select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'datatable_header_input_background', [
                'label'     => esc_html__('Input Background', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list .dataTables_filter input, {{WRAPPER}} .nas-participator-list .dataTables_length select' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'datatable_header_input_padding', [
                'label'      => esc_html__('Input Padding', 'bdthemes-element-pack'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                        '{{WRAPPER}} .nas-participator-list .dataTables_filter input, {{WRAPPER}} .nas-participator-list .dataTables_length select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name'        => 'datatable_header_input_border',
                'label'       => esc_html__('Input Border', 'bdthemes-element-pack'),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .nas-participator-list .dataTables_filter input, {{WRAPPER}} .nas-participator-list .dataTables_length select',
            ]
        );

        $this->add_responsive_control(
            'datatable_header_input_radius', [
                'label'      => esc_html__('Input Radius', 'bdthemes-element-pack'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                        '{{WRAPPER}} .nas-participator-list .dataTables_filter input, {{WRAPPER}} .nas-participator-list .dataTables_length select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(), [
                'name'     => 'datatable_header_input_box_shadow',
                'selector' => '{{WRAPPER}} .nas-participator-list .dataTables_filter input, {{WRAPPER}} .nas-participator-list .dataTables_length select',
            ]
        );

        $this->add_control(
            'datatable_header_space', [
                'label'     => __('Space', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                        'size' => 1,
                ],
                'range'     => [
                        'px' => [
                                'min' => 0,
                                'max' => 40,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list .dataTables_filter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'filter_footer_style', [
                'label' => __('Footer', 'bdthemes-element-pack'),
            ]
        );

        $this->add_control(
            'datatable_footer_text_color', [
                'label'     => esc_html__('Text Color', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list .dataTables_info, {{WRAPPER}} .nas-participator-list .dataTables_paginate' => 'color: {{VALUE}};',
                ],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'datatable_footer_pagination_color', [
                'label'     => esc_html__('Pagination Color', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list .dataTables_paginate a' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'datatable_footer_pagination_active_color', [
                'label'     => esc_html__('Pagination Active Color', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                        '{{WRAPPER}}.elementor-widget-nas-participator-list .nas-participator-list .dataTables_paginate a.current' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'datatable_footer_space', [
                'label'     => __('Space', 'bdthemes-element-pack'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                        'size' => 1,
                ],
                'range'     => [
                        'px' => [
                                'min' => 0,
                                'max' => 40,
                        ],
                ],
                'selectors' => [
                        '{{WRAPPER}} .nas-participator-list table' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        /**
         * Fetch Table fo events
         * 
         */
        $event_id = $settings[ 'events' ]; 

        $participator_list = wd_frontend_fetch_participator_by_event($event_id);
        $id                = 'nas-participator-list-' . $this->get_id();

        if ( 'table_responsive_no' == $settings[ 'table_responsive_control' ] ) {
            $this->add_render_attribute('nas-participator-list-wrapper', 'class', [
                    'bdt-table']);
        }

        if ( 'table_responsive_1' == $settings[ 'table_responsive_control' ] ) {
            $this->add_render_attribute('nas-participator-list-wrapper', 'class', [
                    'bdt-table', 'bdt-table-responsive']);
        }

        if ( 'table_responsive_2' == $settings[ 'table_responsive_control' ] ) {
            $this->add_render_attribute('nas-participator-list-wrapper', 'class', [
                    'bdt-table', 'bdt-table-default-responsive']);
        }

        if ( 'horizontal_scroll' == $settings[ 'table_responsive_control' ] ) {
            $this->add_render_attribute('nas-participator-list-wrapper', 'class', [
                    'bdt-table', 'bdt-overflow-auto']);
        }

        $this->add_render_attribute(
            [
                    'nas-participator-list-wrapper' => [
                            'id'            => $id,
                            'class'         => 'nas-participator-list',
                            'data-settings' => [
                                    wp_json_encode([
                                            'id' => '#' . $id,
                                    ]),
                            ],
                    ],
            ]
        );
        ?>
        <div <?php echo $this->get_render_attribute_string('nas-participator-list-wrapper'); ?>>

            <table id="<?php echo $id . '-table'; ?>" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Group</th>
                        <th>Phone</th>
                        <?php
                        if($settings['show_certificate'] == 'yes'){
                            echo '<th>Certificate</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ( !empty($participator_list) ) {
                        foreach ( $participator_list as $row ) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $row->name; ?>
                                </td>
                                <td>
                                    <?php echo $row->category_name; ?>
                                </td>
                                <td>
                                    <?php echo substr(stringToSecret(str_replace('+88', '', $row->phone)), 0, 11); ?>
                                </td>
                                <?php if($settings['show_certificate'] == 'yes'): ?>
                                <td>
                                    <a target="_blank" href="<?php echo $settings[ 'certificate_link' ][ 'url' ] . '?id=' . $row->id . '&verify=' . md5($row->id); ?>">
                                        Download
                                    </a>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>
            </table>


        </div>
        <?php
    }

}
