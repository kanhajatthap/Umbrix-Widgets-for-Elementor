<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Icon_Box_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_icon_box';
    }

    public function get_title() {
        return 'Icon Box';
    }

    public function get_icon() {
        return 'eicon-icon-box';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_icon_box_section',
            [
                'label' => 'Icon Box',
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => 'Icon',
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-rocket',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Our Value Prop',
                'placeholder' => 'Enter title',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => 'Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Add a short, compelling description for this value proposition.',
                'rows' => 4,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => 'Link (optional)',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_responsive_control(
            'position',
            [
                'label' => 'Position',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [ 'title' => 'Top', 'icon' => 'eicon-v-align-top' ],
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-h-align-left' ],
                ],
                'default' => 'top',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_icon_box_icon_style',
            [
                'label' => 'Icon',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => 'Size',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 14, 'max' => 120 ] ],
                'default' => [ 'size' => 30, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ], '%' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => 'Spacing / Margin',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_icon_box_content_style',
            [
                'label' => 'Content',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-icon-box-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_gap',
            [
                'label' => 'Title Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .bdea-icon-box-desc',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => 'Description Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-icon-box-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $icon     = ! empty( $settings['selected_icon']['value'] ) ? $settings['selected_icon']['value'] : '';
        $title    = $settings['title'];
        $desc     = $settings['description'];
        $position = ! empty( $settings['position'] ) ? $settings['position'] : 'top';
        $has_link = ! empty( $settings['link']['url'] );

        $class = 'bdea-icon-box-widget bdea-icon-box-pos-' . $position;

        if ( $has_link ) {
            $this->add_render_attribute( 'link', 'href', $settings['link']['url'] );
            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', '_blank' );
            }
            if ( ! empty( $settings['link']['nofollow'] ) ) {
                $this->add_render_attribute( 'link', 'rel', 'nofollow' );
            }
        }
        ?>
        <div class="<?php echo esc_attr( $class ); ?>">
            <?php if ( $icon ) : ?>
                <span class="bdea-icon-box-icon"><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
            <?php endif; ?>

            <div class="bdea-icon-box-content">
                <?php if ( $title ) : ?>
                    <h3 class="bdea-icon-box-title"><?php echo esc_html( $title ); ?></h3>
                <?php endif; ?>

                <?php if ( $desc ) : ?>
                    <p class="bdea-icon-box-desc"><?php echo esc_html( $desc ); ?></p>
                <?php endif; ?>

                <?php if ( $has_link ) : ?>
                    <a class="bdea-icon-box-link" <?php echo $this->get_render_attribute_string( 'link' ); ?>>Learn More &#8594;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}