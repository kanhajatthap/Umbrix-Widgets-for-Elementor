<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Link_In_Bio_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_link_in_bio';
    }

    public function get_title() {
        return 'Link in Bio';
    }

    public function get_icon() {
        return 'eicon-link';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_link_in_bio_section',
            [
                'label' => __( 'Link in Bio', 'elementskey' ),
            ]
        );

        $this->add_control(
            'bio_avatar',
            [
                'label' => __( 'Avatar', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'bio_name',
            [
                'label' => __( 'Name', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Jane Doe', 'elementskey' ),
            ]
        );

        $this->add_control(
            'bio_handle',
            [
                'label' => __( 'Handle / Subtitle', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '@janedoe',
            ]
        );

        $this->add_control(
            'bio_text',
            [
                'label' => __( 'Bio', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Welcome to my little corner of the internet.',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'bio_link_label',
            [
                'label' => __( 'Label', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'My Link', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'bio_link_url',
            [
                'label' => __( 'URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $repeater->add_control(
            'bio_link_icon',
            [
                'label' => __( 'Icon', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-link',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'bio_link_bg_item',
            [
                'label' => __( 'Custom Background (Optional)', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'description' => __( 'Overrides global link background for this specific item.', 'elementskey' ),
            ]
        );

        $this->add_control(
            'bio_links',
            [
                'label' => __( 'Links', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'bio_link_label' => __( 'My Website', 'elementskey' ), 'bio_link_url' => [ 'url' => 'https://example.com' ] ],
                    [ 'bio_link_label' => __( 'My Portfolio', 'elementskey' ), 'bio_link_url' => [ 'url' => 'https://example.com/portfolio' ] ],
                    [ 'bio_link_label' => __( 'Contact Me', 'elementskey' ), 'bio_link_url' => [ 'url' => 'https://example.com/contact' ] ],
                ],
                'title_field' => '{{{ bio_link_label }}}',
            ]
        );

        $this->end_controls_section();

        // Style: Card / Container
        $this->start_controls_section(
            'elementskey_link_in_bio_container_style',
            [
                'label' => __( 'Container', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bio_page_bg',
            [
                'label' => __( 'Background Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bio_container_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'bio_container_border',
                'selector' => '{{WRAPPER}} .elementskey-bio-widget',
            ]
        );

        $this->add_responsive_control(
            'bio_container_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-widget' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bio_container_shadow',
                'selector' => '{{WRAPPER}} .elementskey-bio-widget',
            ]
        );

        $this->end_controls_section();

        // Style: Profile Info (Name, Handle, Text)
        $this->start_controls_section(
            'elementskey_link_in_bio_info_style',
            [
                'label' => __( 'Profile Info', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bio_name_color',
            [
                'label' => __( 'Name Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'bio_name_typography',
                'selector' => '{{WRAPPER}} .elementskey-bio-name',
            ]
        );

        $this->add_control(
            'bio_handle_color',
            [
                'label' => __( 'Handle Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-handle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bio_text_color',
            [
                'label' => __( 'Bio Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'bio_text_typography',
                'selector' => '{{WRAPPER}} .elementskey-bio-text',
            ]
        );

        $this->end_controls_section();

        // Style: Links (Background, Colors, Typography, Border, Shadow, Padding, Gap)
        $this->start_controls_section(
            'elementskey_link_in_bio_links_style',
            [
                'label' => __( 'Links', 'elementskey' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'bio_link_tabs' );

        // Normal State
        $this->start_controls_tab(
            'bio_link_tab_normal',
            [
                'label' => __( 'Normal', 'elementskey' ),
            ]
        );

        $this->add_control(
            'bio_global_link_bg',
            [
                'label' => __( 'Background Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-link' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bio_link_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-link, {{WRAPPER}} .elementskey-bio-link-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-bio-link-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'bio_link_tab_hover',
            [
                'label' => __( 'Hover', 'elementskey' ),
            ]
        );

        $this->add_control(
            'bio_global_link_hover_bg',
            [
                'label' => __( 'Background Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-link:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'bio_link_hover_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-link:hover, {{WRAPPER}} .elementskey-bio-link:hover .elementskey-bio-link-icon' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-bio-link:hover .elementskey-bio-link-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'bio_link_typography',
                'selector' => '{{WRAPPER}} .elementskey-bio-link',
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'bio_link_border',
                'selector' => '{{WRAPPER}} .elementskey-bio-link',
            ]
        );

        $this->add_responsive_control(
            'bio_link_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'bio_link_box_shadow',
                'selector' => '{{WRAPPER}} .elementskey-bio-link',
            ]
        );

        $this->add_responsive_control(
            'bio_link_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'bio_link_gap',
            [
                'label' => __( 'Space Between Links', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-links' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $avatar   = ! empty( $settings['bio_avatar']['url'] ) ? $settings['bio_avatar']['url'] : '';
        ?>
        <div class="elementskey-bio-widget">
            <?php if ( $avatar ) : ?>
                <div class="elementskey-bio-avatar">
                    <img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $settings['bio_name'] ); ?>" loading="lazy" />
                </div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_name'] ) ) : ?>
                <div class="elementskey-bio-name"><?php echo esc_html( $settings['bio_name'] ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_handle'] ) ) : ?>
                <div class="elementskey-bio-handle"><?php echo esc_html( $settings['bio_handle'] ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_text'] ) ) : ?>
                <p class="elementskey-bio-text"><?php echo esc_html( $settings['bio_text'] ); ?></p>
            <?php endif; ?>
            <div class="elementskey-bio-links">
                <?php if ( ! empty( $settings['bio_links'] ) ) : ?>
                    <?php foreach ( $settings['bio_links'] as $link ) : ?>
                        <?php
                        $item_bg = ! empty( $link['bio_link_bg_item'] ) ? ' style="background-color: ' . esc_attr( $link['bio_link_bg_item'] ) . ';"' : '';
                        ?>
                        <a class="elementskey-bio-link"
                           href="<?php echo esc_url( ! empty( $link['bio_link_url']['url'] ) ? $link['bio_link_url']['url'] : '#' ); ?>"
                           <?php echo ! empty( $link['bio_link_url']['is_external'] ) ? 'target="_blank" rel="noopener"' : ''; ?>
                           <?php echo $item_bg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?>>
                            <?php if ( ! empty( $link['bio_link_icon']['value'] ) ) : ?>
                                <span class="elementskey-bio-link-icon"><?php \Elementor\Icons_Manager::render_icon( $link['bio_link_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                            <?php endif; ?>
                            <span><?php echo esc_html( $link['bio_link_label'] ); ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
