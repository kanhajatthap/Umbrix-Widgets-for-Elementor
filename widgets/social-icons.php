<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Social_Icons_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_social_icons';
    }

    public function get_title() {
        return 'Social Icons';
    }

    public function get_icon() {
        return 'eicon-social-icons';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function get_social_icons() {
        return [
            'facebook' => [ 'label' => __( 'Facebook', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-facebook-f' ],
            'x' => [ 'label' => __( 'X / Twitter', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-x-twitter' ],
            'twitter' => [ 'label' => __( 'Twitter', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-twitter' ],
            'instagram' => [ 'label' => __( 'Instagram', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-instagram' ],
            'linkedin' => [ 'label' => __( 'LinkedIn', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-linkedin-in' ],
            'youtube' => [ 'label' => __( 'YouTube', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-youtube' ],
            'whatsapp' => [ 'label' => __( 'WhatsApp', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-whatsapp' ],
            'pinterest' => [ 'label' => __( 'Pinterest', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-pinterest-p' ],
            'tiktok' => [ 'label' => __( 'TikTok', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-tiktok' ],
            'telegram' => [ 'label' => __( 'Telegram', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-telegram-plane' ],
            'email' => [ 'label' => __( 'Email', 'elementstack-elementor-addons' ), 'icon' => 'fas fa-envelope' ],
            'github' => [ 'label' => __( 'GitHub', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-github' ],
            'dribbble' => [ 'label' => __( 'Dribbble', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-dribbble' ],
            'behance' => [ 'label' => __( 'Behance', 'elementstack-elementor-addons' ), 'icon' => 'fab fa-behance' ],
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_social_icons_section',
            [
                'label' => __( 'Social Icons', 'elementstack-elementor-addons' ),
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
                'label' => __( 'Social Network', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $options,
                'default' => 'facebook',
            ]
        );

        $repeater->add_control(
            'social_link',
            [
                'label' => __( 'Link', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://facebook.com/yourpage',
            ]
        );

        $this->add_control(
            'social_icons',
            [
                'label' => __( 'Icons', 'elementstack-elementor-addons' ),
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
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icons-widget' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_social_icons_style',
            [
                'label' => __( 'Style', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 14, 'max' => 80 ] ],
                'default' => [ 'size' => 16, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Hover Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#324ac7',
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icon:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ], '%' => [ 'min' => 0, 'max' => 50 ] ],
                'default' => [ 'size' => 50, 'unit' => '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icon' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_gap',
            [
                'label' => __( 'Gap', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default' => [ 'size' => 10, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icons-widget' => 'gap: {{SIZE}}{{UNIT}};',
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
        <div class="bdea-social-icons-widget">
            <?php foreach ( $settings['social_icons'] as $item ) : ?>
                <?php
                $type  = ! empty( $item['social_type'] ) ? $item['social_type'] : 'facebook';
                $icon  = isset( $icons[ $type ] ) ? $icons[ $type ]['icon'] : 'fas fa-circle';
                $label = isset( $icons[ $type ] ) ? $icons[ $type ]['label'] : $type;
                $has_link = ! empty( $item['social_link']['url'] );
                ?>
                <?php if ( $has_link ) : ?>
                    <a class="bdea-social-icon"
                       href="<?php echo esc_url( $item['social_link']['url'] ); ?>"
                       <?php echo ! empty( $item['social_link']['is_external'] ) ? 'target="_blank"' : ''; ?>
                       <?php echo ! empty( $item['social_link']['nofollow'] ) ? 'rel="nofollow"' : ''; ?>
                       aria-label="<?php echo esc_attr( $label ); ?>">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </a>
                <?php else : ?>
                    <span class="bdea-social-icon" aria-label="<?php echo esc_attr( $label ); ?>">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </span>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
    }
}