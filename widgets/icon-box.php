<?php
/**
 * ElementKey Lite
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Icon_Box_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_icon_box';
    }

    public function get_title() {
        return 'Icon Box';
    }

    public function get_icon() {
        return 'eicon-icon-box';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_icon_box_section',
            [
                'label' => __( 'Icon Box', 'elementskey' ),
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => __( 'Icon', 'elementskey' ),
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
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Our Value Prop', 'elementskey' ),
                'placeholder' => __( 'Enter title', 'elementskey' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Add a short, compelling description for this value proposition.',
                'rows' => 4,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link (optional)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_responsive_control(
            'position',
            [
                'label' => __( 'Position', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [ 'title' => __( 'Top', 'elementskey' ), 'icon' => 'eicon-v-align-top' ],
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-h-align-left' ],
                ],
                'default' => 'top',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-widget' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_icon_box_card_style',
            [
                'label' => __( 'Card', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .elementskey-icon-box-widget',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_icon_box_icon_style',
            [
                'label' => __( 'Icon', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 14, 'max' => 120 ] ],
                'default' => [ 'size' => 30, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-icon i, {{WRAPPER}} .elementskey-icon-box-icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ], '%' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_margin',
            [
                'label' => __( 'Spacing / Margin', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_icon_box_content_style',
            [
                'label' => __( 'Content', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementskey-icon-box-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_gap',
            [
                'label' => __( 'Title Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .elementskey-icon-box-desc',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Description Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-icon-box-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $icon     = ! empty( $settings['selected_icon']['value'] ) ? $settings['selected_icon'] : '';
        $title    = $settings['title'];
        $desc     = $settings['description'];
        $position = ! empty( $settings['position'] ) ? $settings['position'] : 'top';
        $has_link = ! empty( $settings['link']['url'] );

        $class = 'elementskey-icon-box-widget elementskey-icon-box-pos-' . $position;

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
                <span class="elementskey-icon-box-icon"><?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?></span>
            <?php endif; ?>

            <div class="elementskey-icon-box-content">
                <?php if ( $title ) : ?>
                    <h3 class="elementskey-icon-box-title"><?php echo esc_html( $title ); ?></h3>
                <?php endif; ?>

                <?php if ( $desc ) : ?>
                    <p class="elementskey-icon-box-desc"><?php echo esc_html( $desc ); ?></p>
                <?php endif; ?>

                <?php if ( $has_link ) : ?>
                    <a class="elementskey-icon-box-link" <?php echo $this->get_render_attribute_string( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor render attributes are escaped internally. ?>>Learn More &#8594;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
