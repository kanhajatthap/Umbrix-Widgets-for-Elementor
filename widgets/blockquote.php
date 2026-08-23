<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Blockquote_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_blockquote';
    }

    public function get_title() {
        return 'Blockquote';
    }

    public function get_icon() {
        return 'eicon-blockquote';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_blockquote_section',
            [
                'label' => __( 'Blockquote', 'elementskey' ),
            ]
        );

        $this->add_control(
            'quote_content',
            [
                'label' => __( 'Quote', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Design is not just what it looks like and feels like. Design is how it works.',
                'rows' => 5,
            ]
        );

        $this->add_control(
            'quote_author',
            [
                'label' => __( 'Author Name', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Steve Jobs', 'elementskey' ),
            ]
        );

        $this->add_control(
            'quote_role',
            [
                'label' => __( 'Author Role', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Entrepreneur', 'elementskey' ),
            ]
        );

        $this->add_responsive_control(
            'quote_align',
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
                    '{{WRAPPER}} .elementskey-blockquote' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_blockquote_quote_style',
            [
                'label' => __( 'Quote', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .elementskey-blockquote-quote',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-blockquote-quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_blockquote_author_style',
            [
                'label' => __( 'Author', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .elementskey-blockquote-author-name',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => __( 'Name Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-blockquote-author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_role_typography',
                'selector' => '{{WRAPPER}} .elementskey-blockquote-author-role',
            ]
        );

        $this->add_control(
            'author_role_color',
            [
                'label' => __( 'Role Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-blockquote-author-role' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $quote       = ! empty( $settings['quote_content'] ) ? $settings['quote_content'] : '';
        $author_name = ! empty( $settings['quote_author'] ) ? $settings['quote_author'] : '';
        $author_role = ! empty( $settings['quote_role'] ) ? $settings['quote_role'] : '';
        ?>
        <blockquote class="elementskey-blockquote">
            <?php if ( $quote ) : ?>
                <div class="elementskey-blockquote-quote"><?php echo wp_kses_post( $quote ); ?></div>
            <?php endif; ?>
            <?php if ( $author_name || $author_role ) : ?>
                <footer class="elementskey-blockquote-author">
                    <?php if ( $author_name ) : ?>
                        <span class="elementskey-blockquote-author-name"><?php echo esc_html( $author_name ); ?></span>
                    <?php endif; ?>
                    <?php if ( $author_role ) : ?>
                        <span class="elementskey-blockquote-author-role"><?php echo esc_html( $author_role ); ?></span>
                    <?php endif; ?>
                </footer>
            <?php endif; ?>
        </blockquote>
        <?php
    }
}