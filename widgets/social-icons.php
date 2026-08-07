<?php
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
            'facebook' => [ 'label' => 'Facebook', 'icon' => 'fab fa-facebook-f' ],
            'x' => [ 'label' => 'X / Twitter', 'icon' => 'fab fa-x-twitter' ],
            'twitter' => [ 'label' => 'Twitter', 'icon' => 'fab fa-twitter' ],
            'instagram' => [ 'label' => 'Instagram', 'icon' => 'fab fa-instagram' ],
            'linkedin' => [ 'label' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in' ],
            'youtube' => [ 'label' => 'YouTube', 'icon' => 'fab fa-youtube' ],
            'whatsapp' => [ 'label' => 'WhatsApp', 'icon' => 'fab fa-whatsapp' ],
            'pinterest' => [ 'label' => 'Pinterest', 'icon' => 'fab fa-pinterest-p' ],
            'tiktok' => [ 'label' => 'TikTok', 'icon' => 'fab fa-tiktok' ],
            'telegram' => [ 'label' => 'Telegram', 'icon' => 'fab fa-telegram-plane' ],
            'email' => [ 'label' => 'Email', 'icon' => 'fas fa-envelope' ],
            'github' => [ 'label' => 'GitHub', 'icon' => 'fab fa-github' ],
            'dribbble' => [ 'label' => 'Dribbble', 'icon' => 'fab fa-dribbble' ],
            'behance' => [ 'label' => 'Behance', 'icon' => 'fab fa-behance' ],
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_social_icons_section',
            [
                'label' => 'Social Icons',
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
                'label' => 'Social Network',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $options,
                'default' => 'facebook',
            ]
        );

        $repeater->add_control(
            'social_link',
            [
                'label' => 'Link',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://facebook.com/yourpage',
            ]
        );

        $this->add_control(
            'social_icons',
            [
                'label' => 'Icons',
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
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
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
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => 'Size',
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
                'label' => 'Color',
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
                'label' => 'Background',
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
                'label' => 'Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-social-icon:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_bg',
            [
                'label' => 'Hover Background',
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
                'label' => 'Border Radius',
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
                'label' => 'Padding',
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
                'label' => 'Gap',
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