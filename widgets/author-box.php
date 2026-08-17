<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Author_Box_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_author_box';
    }

    public function get_title() {
        return 'Author Box';
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_author_box_content',
            [
                'label' => __( 'Content', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label' => __( 'Layout', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __( 'Left', 'elementstack-elementor-addons' ),
                    'top' => __( 'Top', 'elementstack-elementor-addons' ),
                ],
            ]
        );

        $this->add_control(
            'show_avatar',
            [
                'label' => __( 'Show Avatar', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'avatar_size',
            [
                'label' => __( 'Avatar Size', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 96,
                'min' => 32,
                'max' => 256,
                'condition' => [ 'show_avatar' => 'yes' ],
            ]
        );

        $this->add_control(
            'show_name',
            [
                'label' => __( 'Show Name', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => __( 'Show Description', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_website',
            [
                'label' => __( 'Show Website Link', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_author_box_style',
            [
                'label' => __( 'Author Box', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'box_bg',
            [
                'label' => __( 'Background', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} .bdea-author-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'box_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'default' => [ 'size' => 8, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-author-box' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'box_padding',
            [
                'label' => __( 'Padding', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-author-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .bdea-author-name',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __( 'Name Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-author-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .bdea-author-description',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Description Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-author-description' => 'color: {{VALUE}};',
                ],
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
            echo '<div class="bdea-loop-grid-empty">No post found.</div>';
            return;
        }

        $author_id   = (int) get_post_field( 'post_author', $post_id );
        $author_name = get_the_author_meta( 'display_name', $author_id );
        $description = get_the_author_meta( 'description', $author_id );
        $website_url = get_the_author_meta( 'user_url', $author_id );
        $author_url  = get_author_posts_url( $author_id );

        $avatar_size  = ! empty( $settings['avatar_size'] ) ? absint( $settings['avatar_size'] ) : 96;
        $layout_class = ( 'top' === $settings['layout'] ) ? 'bdea-author-box-top' : 'bdea-author-box-left';
        ?>
        <div class="bdea-author-box <?php echo esc_attr( $layout_class ); ?>">
            <?php if ( 'yes' === $settings['show_avatar'] ) : ?>
                <div class="bdea-author-avatar">
                    <a href="<?php echo esc_url( $author_url ); ?>"><?php echo get_avatar( $author_id, $avatar_size ); ?></a>
                </div>
            <?php endif; ?>

            <div class="bdea-author-body">
                <?php if ( 'yes' === $settings['show_name'] ) : ?>
                    <div class="bdea-author-name">
                        <a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a>
                    </div>
                <?php endif; ?>

                <?php if ( 'yes' === $settings['show_description'] && $description ) : ?>
                    <div class="bdea-author-description"><?php echo esc_html( $description ); ?></div>
                <?php endif; ?>

                <?php if ( 'yes' === $settings['show_website'] && $website_url ) : ?>
                    <a class="bdea-author-website" href="<?php echo esc_url( $website_url ); ?>" rel="nofollow"><?php echo esc_html( 'Visit Website' ); ?></a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}