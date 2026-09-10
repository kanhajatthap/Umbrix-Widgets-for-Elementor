<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Share_It_Widget extends \Elementor\Widget_Base {
    protected function elementskey_share_it_get_current_context_data() {
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

        if ( empty( $current_url ) ) {
            $current_url = home_url( '/' );
        }

        return [
            'url'   => esc_url_raw( (string) $current_url ),
            'title' => wp_strip_all_tags( (string) $current_title ),
        ];
    }

    public function get_name() {
        return 'elementskey_share_it';
    }

    public function get_title() {
        return 'Share It';
    }

    public function get_icon() {
        return 'eicon-share';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-share-it-style', 'elementor-icons-fa-solid', 'elementor-icons-fa-brands' ];
    }

    public function get_script_depends() {
        return [ 'elementskey-share-it-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_share_it_content_section',
            [
                'label' => __( 'Content', 'elementskey' ),
            ]
        );

        $this->add_control(
            'elementskey_share_it_show_title',
            [
                'label' => __( 'Show Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'elementskey_share_it_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Share This Page', 'elementskey' ),
                'condition' => [
                    'elementskey_share_it_show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_facebook',
            [
                'label' => __( 'Facebook', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'elementskey_share_it_x',
            [
                'label' => __( 'X (Twitter)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'elementskey_share_it_linkedin',
            [
                'label' => __( 'LinkedIn', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'elementskey_share_it_whatsapp',
            [
                'label' => __( 'WhatsApp', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'elementskey_share_it_telegram',
            [
                'label' => __( 'Telegram', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'elementskey_share_it_email',
            [
                'label' => __( 'Email', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'elementskey_share_it_copy_link',
            [
                'label' => __( 'Copy Link', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'elementskey_share_it_pinterest',
            [
                'label' => __( 'Pinterest', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_share_it_style_section',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'elementskey_share_it_alignment',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'elementskey' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'elementskey' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'elementskey' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors_dictionary' => [
                    'left' => 'flex-start',
                    'center' => 'center',
                    'right' => 'flex-end',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-share-it-links' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-share-it-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_icon_color',
            [
                'label' => __( 'Icon Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-share-it-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-share-it-link i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-share-it-link svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-share-it-link:hover, {{WRAPPER}} .elementskey-share-it-link:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-share-it-link:hover i, {{WRAPPER}} .elementskey-share-it-link:focus i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-share-it-link:hover svg, {{WRAPPER}} .elementskey-share-it-link:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_background_color',
            [
                'label' => __( 'Background Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f3a76',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-share-it-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_hover_background',
            [
                'label' => __( 'Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#365ea8',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-share-it-link:hover, {{WRAPPER}} .elementskey-share-it-link:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_copy_success_bg',
            [
                'label' => __( 'Copied State Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#198754',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-share-it-copy.is-copied' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_icon_size',
            [
                'label' => __( 'Icon Size', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-share-it-link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: calc({{SIZE}}{{UNIT}} / 2.2);',
                    '{{WRAPPER}} .elementskey-share-it-link i' => 'font-size: calc({{SIZE}}{{UNIT}} / 2.2);',
                    '{{WRAPPER}} .elementskey-share-it-link svg' => 'height: calc({{SIZE}}{{UNIT}} / 2.2); width: calc({{SIZE}}{{UNIT}} / 2.2);',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_icon_spacing',
            [
                'label' => __( 'Icon Spacing', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-share-it-links' => 'display: flex; flex-wrap: wrap; gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
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
                    '{{WRAPPER}} .elementskey-share-it-link' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-share-it-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'elementskey_share_it_margin',
            [
                'label' => __( 'Margin', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-share-it-widget' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $context       = $this->elementskey_share_it_get_current_context_data();
        $current_url   = $context['url'];
        $current_title = $context['title'];

        if ( empty( $current_url ) ) {
            return;
        }

        $encoded_url   = rawurlencode( $current_url );
        $encoded_title = rawurlencode( $current_title );

        $links = [
            'facebook' => [
                'enabled' => ( ! empty( $settings['elementskey_share_it_facebook'] ) ),
                'label' => __( 'Facebook', 'elementskey' ),
                'icon' => [ 'value' => 'fab fa-facebook-f', 'library' => 'fa-brands' ],
                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encoded_url,
            ],
            'x' => [
                'enabled' => ( ! empty( $settings['elementskey_share_it_x'] ) ),
                'label' => 'X',
                'icon' => [ 'value' => 'fab fa-twitter', 'library' => 'fa-brands' ],
                'url' => 'https://twitter.com/intent/tweet?url=' . $encoded_url . '&text=' . $encoded_title,
            ],
            'linkedin' => [
                'enabled' => ( ! empty( $settings['elementskey_share_it_linkedin'] ) ),
                'label' => __( 'LinkedIn', 'elementskey' ),
                'icon' => [ 'value' => 'fab fa-linkedin-in', 'library' => 'fa-brands' ],
                'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encoded_url,
            ],
            'whatsapp' => [
                'enabled' => ( ! empty( $settings['elementskey_share_it_whatsapp'] ) ),
                'label' => __( 'WhatsApp', 'elementskey' ),
                'icon' => [ 'value' => 'fab fa-whatsapp', 'library' => 'fa-brands' ],
                'url' => 'https://api.whatsapp.com/send?text=' . rawurlencode( $current_title . ' ' . $current_url ),
            ],
            'telegram' => [
                'enabled' => ( ! empty( $settings['elementskey_share_it_telegram'] ) ),
                'label' => __( 'Telegram', 'elementskey' ),
                'icon' => [ 'value' => 'fab fa-telegram-plane', 'library' => 'fa-brands' ],
                'url' => 'https://t.me/share/url?url=' . $encoded_url . '&text=' . $encoded_title,
            ],
            'email' => [
                'enabled' => ( ! empty( $settings['elementskey_share_it_email'] ) ),
                'label' => __( 'Email', 'elementskey' ),
                'icon' => [ 'value' => 'fas fa-envelope', 'library' => 'fa-solid' ],
                'url' => 'mailto:?subject=' . $encoded_title . '&body=' . rawurlencode( $current_url ),
            ],
            'copy' => [
                'enabled' => ( ! empty( $settings['elementskey_share_it_copy_link'] ) ),
                'label' => __( 'Copy Link', 'elementskey' ),
                'icon' => [ 'value' => 'fas fa-copy', 'library' => 'fa-solid' ],
                'url' => $current_url,
            ],
            'pinterest' => [
                'enabled' => ( ! empty( $settings['elementskey_share_it_pinterest'] ) ),
                'label' => __( 'Pinterest', 'elementskey' ),
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
        <div class="elementskey-share-it-widget">
            <?php if ( ! empty( $settings['elementskey_share_it_show_title'] ) && ! empty( $settings['elementskey_share_it_title'] ) ) : ?>
                <h3 class="elementskey-share-it-title"><?php echo esc_html( $settings['elementskey_share_it_title'] ); ?></h3>
            <?php endif; ?>

            <div class="elementskey-share-it-links" role="group" aria-label="Share links">
                <?php foreach ( $links as $network => $share_link ) : ?>
                    <?php if ( empty( $share_link['enabled'] ) ) : ?>
                        <?php continue; ?>
                    <?php endif; ?>

                    <?php if ( 'copy' === $network ) : ?>
                        <a
                            href="<?php echo esc_url( $share_link['url'] ); ?>"
                            class="elementskey-share-it-link elementskey-share-it-copy"
                            data-copy-url="<?php echo esc_url( $current_url ); ?>"
                            aria-label="<?php echo esc_attr( $share_link['label'] ); ?>"
                            title="<?php echo esc_attr( $share_link['label'] ); ?>"
                        >
                            <?php \Elementor\Icons_Manager::render_icon( $share_link['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                    <?php elseif ( 'email' === $network ) : ?>
                        <a
                            href="<?php echo esc_url( $share_link['url'] ); ?>"
                            class="elementskey-share-it-link"
                            aria-label="<?php echo esc_attr( $share_link['label'] ); ?>"
                            title="<?php echo esc_attr( $share_link['label'] ); ?>"
                        >
                            <?php \Elementor\Icons_Manager::render_icon( $share_link['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </a>
                    <?php else : ?>
                        <a
                            href="<?php echo esc_url( $share_link['url'] ); ?>"
                            class="elementskey-share-it-link"
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
