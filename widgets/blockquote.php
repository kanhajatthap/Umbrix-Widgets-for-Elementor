<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Blockquote_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_blockquote';
    }

    public function get_title() {
        return 'Blockquote';
    }

    public function get_icon() {
        return 'eicon-blockquote';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_blockquote_section',
            [
                'label' => __( 'Blockquote', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'quote_content',
            [
                'label' => __( 'Quote', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Design is not just what it looks like and feels like. Design is how it works.',
                'rows' => 5,
            ]
        );

        $this->add_control(
            'quote_author',
            [
                'label' => __( 'Author Name', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Steve Jobs', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'quote_role',
            [
                'label' => __( 'Author Role', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Entrepreneur', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_responsive_control(
            'quote_align',
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
                    '{{WRAPPER}} .bdea-blockquote' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_blockquote_quote_style',
            [
                'label' => __( 'Quote', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'quote_typography',
                'selector' => '{{WRAPPER}} .bdea-blockquote-quote',
            ]
        );

        $this->add_control(
            'quote_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-blockquote-quote' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_blockquote_author_style',
            [
                'label' => __( 'Author', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_typography',
                'selector' => '{{WRAPPER}} .bdea-blockquote-author-name',
            ]
        );

        $this->add_control(
            'author_color',
            [
                'label' => __( 'Name Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-blockquote-author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'author_role_typography',
                'selector' => '{{WRAPPER}} .bdea-blockquote-author-role',
            ]
        );

        $this->add_control(
            'author_role_color',
            [
                'label' => __( 'Role Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-blockquote-author-role' => 'color: {{VALUE}};',
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
        <blockquote class="bdea-blockquote">
            <?php if ( $quote ) : ?>
                <div class="bdea-blockquote-quote"><?php echo wp_kses_post( $quote ); ?></div>
            <?php endif; ?>
            <?php if ( $author_name || $author_role ) : ?>
                <footer class="bdea-blockquote-author">
                    <?php if ( $author_name ) : ?>
                        <span class="bdea-blockquote-author-name"><?php echo esc_html( $author_name ); ?></span>
                    <?php endif; ?>
                    <?php if ( $author_role ) : ?>
                        <span class="bdea-blockquote-author-role"><?php echo esc_html( $author_role ); ?></span>
                    <?php endif; ?>
                </footer>
            <?php endif; ?>
        </blockquote>
        <?php
    }
}