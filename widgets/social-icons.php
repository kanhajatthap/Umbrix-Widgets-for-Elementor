<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Social_Icons_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_social_icons';
    }

    public function get_title() {
        return 'Social Icons';
    }

    public function get_icon() {
        return 'eicon-social-icons';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function get_social_icons() {
        return [
            'facebook' => [ 'label' => __( 'Facebook', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ] ],
            'x' => [ 'label' => __( 'X / Twitter', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-x-twitter', 'library' => 'fa-brands' ] ],
            'twitter' => [ 'label' => __( 'Twitter', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ] ],
            'instagram' => [ 'label' => __( 'Instagram', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-instagram', 'library' => 'fa-brands' ] ],
            'linkedin' => [ 'label' => __( 'LinkedIn', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ] ],
            'youtube' => [ 'label' => __( 'YouTube', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-youtube', 'library' => 'fa-brands' ] ],
            'whatsapp' => [ 'label' => __( 'WhatsApp', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-whatsapp', 'library' => 'fa-brands' ] ],
            'pinterest' => [ 'label' => __( 'Pinterest', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ] ],
            'tiktok' => [ 'label' => __( 'TikTok', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-tiktok', 'library' => 'fa-brands' ] ],
            'telegram' => [ 'label' => __( 'Telegram', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-telegram-plane', 'library' => 'fa-brands' ] ],
            'email' => [ 'label' => __( 'Email', 'elementskey' ), 'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ] ],
            'github' => [ 'label' => __( 'GitHub', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-github', 'library' => 'fa-brands' ] ],
            'dribbble' => [ 'label' => __( 'Dribbble', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-dribbble', 'library' => 'fa-brands' ] ],
            'behance' => [ 'label' => __( 'Behance', 'elementskey' ), 'icon' => [ 'value' => 'fab fa-behance', 'library' => 'fa-brands' ] ],
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_social_icons_section',
            [
                'label' => __( 'Social Icons', 'elementskey' ),
            ]
        );

        $icons = $this->get_social_icons();

        $options = [];
        foreach ( $icons as $key => $data ) {
            $options[ $key ] = $data['label'];
        }

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'social_type',
            [
                'label' => __( 'Social Network', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $options,
                'default' => 'facebook',
            ]
        );

        $repeater->add_control(
            'social_link',
            [
                'label' => __( 'Link', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://facebook.com/yourpage',
            ]
        );

        $this->add_control(
            'social_icons',
            [
                'label' => __( 'Icons', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'social_type' => 'facebook', 'social_link' => [ 'url' => 'https://facebook.com' ] ],
                    [ 'social_type' => 'x', 'social_link' => [ 'url' => 'https://x.com' ] ],
                    [ 'social_type' => 'instagram', 'social_link' => [ 'url' => 'https://instagram.com' ] ],
                    [ 'social_type' => 'linkedin', 'social_link' => [ 'url' => 'https://linkedin.com' ] ],
                ],
                'title_field' => '{{{ social_type }}}',
            ]
        );

        $this->add_responsive_control(
            'social_align',
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
                    '{{WRAPPER}} .elementskey-social-icons-widget' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_social_icons_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 14, 'max' => 80 ] ],
                'default' => [ 'size' => 16, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-social-icon i, {{WRAPPER}} .elementskey-social-icon svg' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-social-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-social-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-social-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#324ac7',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-social-icon:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ], '%' => [ 'min' => 0, 'max' => 50 ] ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-social-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elementskey-social-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_gap',
            [
                'label' => __( 'Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default' => [ 'size' => 10, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-social-icons-widget' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['social_icons'] ) ) {
            return;
        }

        $icons = $this->get_social_icons();
        ?>
        <div class="elementskey-social-icons-widget">
            <?php foreach ( $settings['social_icons'] as $item ) : ?>
                <?php
                $type  = ! empty( $item['social_type'] ) ? $item['social_type'] : 'facebook';
                $icon_data = isset( $icons[ $type ] ) ? $icons[ $type ]['icon'] : [ 'value' => 'fas fa-circle', 'library' => 'fa-solid' ];
                $icon      = is_array( $icon_data ) ? $icon_data : [ 'value' => $icon_data, 'library' => 'fa-brands' ];
                $label = isset( $icons[ $type ] ) ? $icons[ $type ]['label'] : $type;
                $has_link = ! empty( $item['social_link']['url'] );
                ?>
                <?php if ( $has_link ) : ?>
                    <a class="elementskey-social-icon"
                       href="<?php echo esc_url( $item['social_link']['url'] ); ?>"
                       <?php echo ! empty( $item['social_link']['is_external'] ) ? 'target="_blank"' : ''; ?>
                       <?php echo ! empty( $item['social_link']['nofollow'] ) ? 'rel="nofollow"' : ''; ?>
                       aria-label="<?php echo esc_attr( $label ); ?>">
                        <?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                    </a>
                <?php else : ?>
                    <span class="elementskey-social-icon" aria-label="<?php echo esc_attr( $label ); ?>">
                        <?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); ?>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
    }
}