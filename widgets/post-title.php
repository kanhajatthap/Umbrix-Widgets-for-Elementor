<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Post_Title_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_post_title';
    }

    public function get_title() {
        return 'Post Title';
    }

    public function get_icon() {
        return 'eicon-post-title';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_post_title_content',
            [
                'label' => __( 'Content', 'elementskey' ),
            ]
        );

        $this->add_control(
            'link_to_post',
            [
                'label' => __( 'Link to Post', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'html_tag',
            [
                'label' => __( 'HTML Tag', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h1',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'p' => 'P',
                    'div' => 'DIV',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_post_title_style',
            [
                'label' => __( 'Title', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementskey-post-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-post-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_decoration',
            [
                'label' => __( 'Hover Decoration', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => 'None',
                    'underline' => __( 'Underline', 'elementskey' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-title a:hover' => 'text-decoration: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_align',
            [
                'label' => __( 'Alignment', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementskey' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementskey' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementskey' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-post-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .elementskey-post-title',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $post_id = get_the_ID();

        if ( ! $post_id ) {
            $queried = get_queried_object();
            if ( $queried instanceof \WP_Post ) {
                $post_id = $queried->ID;
            }
        }

        if ( ! $post_id ) {
            echo '<div class="elementskey-loop-grid-empty">No post found.</div>';
            return;
        }

        $tag = ! empty( $settings['html_tag'] ) ? $settings['html_tag'] : 'h1';

        if ( ! in_array( $tag, [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div' ], true ) ) {
            $tag = 'h1';
        }

        $title = get_the_title( $post_id );
        ?>
        <<?php echo esc_attr( $tag ); ?> class="elementskey-post-title">
            <?php if ( 'yes' === $settings['link_to_post'] ) : ?>
                <a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( $title ); ?></a>
            <?php else : ?>
                <?php echo esc_html( $title ); ?>
            <?php endif; ?>
        </<?php echo esc_attr( $tag ); ?>>
        <?php
    }
}