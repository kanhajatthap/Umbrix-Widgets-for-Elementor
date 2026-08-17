<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Share_It_Widget extends \Elementor\Widget_Base {
    protected function bdea_share_it_get_current_context_data() {
        $current_id    = (int) get_queried_object_id();
        $current_url   = '';
        $current_title = wp_get_document_title();

        if ( $current_id > 0 ) {
            $current_url = get_permalink( $current_id );

            if ( empty( $current_title ) ) {
                $current_title = get_the_title( $current_id );
            }
        }

        if ( empty( $current_url ) ) {
            global $wp;

            if ( isset( $wp ) && ! empty( $wp->request ) ) {
                $current_url = home_url( user_trailingslashit( $wp->request ) );
            } elseif ( is_front_page() || is_home() ) {
                $current_url = home_url( '/' );
            }
        }

        return [
            'url'   => esc_url_raw( (string) $current_url ),
            'title' => wp_strip_all_tags( (string) $current_title ),
        ];
    }

    public function get_name() {
        return 'bdea_share_it';
    }

    public function get_title() {
        return 'Share It';
    }

    public function get_icon() {
        return 'eicon-share';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        $depends = [ 'bdea-share-it-style' ];

        $depends[] = 'elementor-icons-fa-solid';
        $depends[] = 'elementor-icons-fa-brands';

        return $depends;
    }

    public function get_script_depends() {
        return [ 'bdea-share-it-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_share_it_content_section',
            [
                'label' => __( 'Content', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'bdea_share_it_show_title',
            [
                'label' => __( 'Show Title', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'bdea_share_it_title',
            [
                'label' => __( 'Title', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Share This Page', 'elementstack-elementor-addons' ),
                'condition' => [
                    'bdea_share_it_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_facebook',
            [
                'label' => __( 'Facebook', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'bdea_share_it_x',
            [
                'label' => __( 'X (Twitter)', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'bdea_share_it_linkedin',
            [
                'label' => __( 'LinkedIn', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'bdea_share_it_whatsapp',
            [
                'label' => __( 'WhatsApp', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'bdea_share_it_telegram',
            [
                'label' => __( 'Telegram', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'bdea_share_it_email',
            [
                'label' => __( 'Email', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'bdea_share_it_copy_link',
            [
                'label' => __( 'Copy Link', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'bdea_share_it_pinterest',
            [
                'label' => __( 'Pinterest', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_share_it_style_section',
            [
                'label' => __( 'Style', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bdea_share_it_alignment',
            [
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementstack-elementor-addons' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'center' => 'center',
                    'right' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-links' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .bdea-share-it-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_icon_color',
            [
                'label' => __( 'Icon Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-share-it-link svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_hover_color',
            [
                'label' => __( 'Hover Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-link:hover, {{WRAPPER}} .bdea-share-it-link:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-share-it-link:hover svg, {{WRAPPER}} .bdea-share-it-link:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_background_color',
            [
                'label' => __( 'Background Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f3a76',
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_hover_background',
            [
                'label' => __( 'Hover Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#365ea8',
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-link:hover, {{WRAPPER}} .bdea-share-it-link:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_icon_size',
            [
                'label' => __( 'Icon Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 20, 'max' => 80 ],
                ],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 3);',
                    '{{WRAPPER}} .bdea-share-it-link svg' => 'height: calc({{SIZE}}{{UNIT}} / 3); width: calc({{SIZE}}{{UNIT}} / 3);',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 50 ],
                ],
                'default' => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-links' => 'display: flex; flex-wrap: wrap; gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_border_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 100 ],
                ],
                'default' => [
                    'size' => 999,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-link' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'bdea_share_it_margin',
            [
                'label' => __( 'Margin', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-share-it-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $context       = $this->bdea_share_it_get_current_context_data();
        $current_url   = $context['url'];
        $current_title = $context['title'];

        if ( empty( $current_url ) ) {
            return;
        }

        $encoded_url   = rawurlencode( $current_url );
        $encoded_title = rawurlencode( $current_title );

        $links = [
            'facebook' => [
                'enabled' => ( ! empty( $settings['bdea_share_it_facebook'] ) ),
                'label' => __( 'Facebook', 'elementstack-elementor-addons' ),
                'icon' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url,
            ],
            'x' => [
                'enabled' => ( ! empty( $settings['bdea_share_it_x'] ) ),
                'label' => 'X',
                'icon' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
                'url' => 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title,
            ],
            'linkedin' => [
                'enabled' => ( ! empty( $settings['bdea_share_it_linkedin'] ) ),
                'label' => __( 'LinkedIn', 'elementstack-elementor-addons' ),
                'icon' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ],
                'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded_url,
            ],
            'whatsapp' => [
                'enabled' => ( ! empty( $settings['bdea_share_it_whatsapp'] ) ),
                'label' => __( 'WhatsApp', 'elementstack-elementor-addons' ),
                'icon' => [ 'value' => 'fab fa-whatsapp', 'library' => 'fa-brands' ],
                'url' => 'https://api.whatsapp.com/send?text=' . rawurlencode( $current_title . ' ' . $current_url ),
            ],
            'telegram' => [
                'enabled' => ( ! empty( $settings['bdea_share_it_telegram'] ) ),
                'label' => __( 'Telegram', 'elementstack-elementor-addons' ),
                'icon' => [ 'value' => 'fab fa-telegram-plane', 'library' => 'fa-brands' ],
                'url' => 'https://t.me/share/url?url=' . $encoded_url . '&text=' . $encoded_title,
            ],
            'email' => [
                'enabled' => ( ! empty( $settings['bdea_share_it_email'] ) ),
                'label' => __( 'Email', 'elementstack-elementor-addons' ),
                'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ],
                'url' => 'mailto:?subject=' . $encoded_title . '&body=' . rawurlencode( $current_url ),
            ],
            'copy' => [
                'enabled' => ( ! empty( $settings['bdea_share_it_copy_link'] ) ),
                'label' => __( 'Copy Link', 'elementstack-elementor-addons' ),
                'icon' => [ 'value' => 'fas fa-copy', 'library' => 'fa-solid' ],
                'url' => $current_url,
            ],
            'pinterest' => [
                'enabled' => ( ! empty( $settings['bdea_share_it_pinterest'] ) ),
                'label' => __( 'Pinterest', 'elementstack-elementor-addons' ),
                'icon' => [ 'value' => 'fab fa-pinterest-p', 'library' => 'fa-brands' ],
                'url' => 'https://pinterest.com/pin/create/button/?url=' . $encoded_url . '&description=' . $encoded_title,
            ],
        ];

        $has_enabled_links = false;
        foreach ( $links as $share_link ) {
            if ( ! empty( $share_link['enabled'] ) ) {
                $has_enabled_links = true;
                break;
            }
        }

        if ( ! $has_enabled_links ) {
            return;
        }
        ?>
        <div class="bdea-share-it-widget">
            <?php if ( ! empty( $settings['bdea_share_it_show_title'] ) && ! empty( $settings['bdea_share_it_title'] ) ) : ?>
                <h3 class="bdea-share-it-title"><?php echo esc_html( $settings['bdea_share_it_title'] ); ?></h3>
            <?php endif; ?>

            <div class="bdea-share-it-links" role="group" aria-label="Share links">
                <?php foreach ( $links as $network => $share_link ) : ?>
                    <?php if ( empty( $share_link['enabled'] ) ) : ?>
                        <?php continue; ?>
                    <?php endif; ?>

                    <?php if ( 'copy' === $network ) : ?>
                        <a
                            href="<?php echo esc_url( $share_link['url'] ); ?>"
                            class="bdea-share-it-link bdea-share-it-copy"
                            data-copy-url="<?php echo esc_url( $current_url ); ?>"
                            aria-label="<?php echo esc_attr( $share_link['label'] ); ?>"
                            title="<?php echo esc_attr( $share_link['label'] ); ?>"
                        >
                            <?php \Elementor\Icons_Manager::render_icon( $share_link['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                    <?php elseif ( 'email' === $network ) : ?>
                        <a
                            href="<?php echo esc_url( $share_link['url'] ); ?>"
                            class="bdea-share-it-link"
                            aria-label="<?php echo esc_attr( $share_link['label'] ); ?>"
                            title="<?php echo esc_attr( $share_link['label'] ); ?>"
                        >
                            <?php \Elementor\Icons_Manager::render_icon( $share_link['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                    <?php else : ?>
                        <a
                            href="<?php echo esc_url( $share_link['url'] ); ?>"
                            class="bdea-share-it-link"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="<?php echo esc_attr( $share_link['label'] ); ?>"
                            title="<?php echo esc_attr( $share_link['label'] ); ?>"
                        >
                            <?php \Elementor\Icons_Manager::render_icon( $share_link['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
