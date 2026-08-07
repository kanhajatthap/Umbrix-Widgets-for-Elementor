<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Sitemap_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_sitemap';
    }

    public function get_title() {
        return 'Sitemap';
    }

    public function get_icon() {
        return 'eicon-sitemap';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_sitemap_section',
            [
                'label' => 'Sitemap',
            ]
        );

        $this->add_control(
            'sitemap_post_types',
            [
                'label' => 'Post Types',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => bdea_widget_post_types(),
                'default' => [ 'page' ],
                'multiple' => true,
            ]
        );

        $this->add_control(
            'sitemap_show_count',
            [
                'label' => 'Show Post Count',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'sitemap_orderby',
            [
                'label' => 'Order By',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'menu_order',
                'options' => [
                    'menu_order' => 'Menu Order',
                    'title' => 'Title',
                    'date' => 'Date',
                ],
            ]
        );

        $this->add_control(
            'sitemap_order',
            [
                'label' => 'Order',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'ASC',
                'options' => [
                    'ASC' => 'Ascending',
                    'DESC' => 'Descending',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_sitemap_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'sitemap_link_color',
            [
                'label' => 'Link Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-sitemap a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sitemap_title_color',
            [
                'label' => 'Post Type Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-sitemap-type-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $post_types = ! empty( $settings['sitemap_post_types'] ) ? (array) $settings['sitemap_post_types'] : [ 'page' ];
        $orderby    = ! empty( $settings['sitemap_orderby'] ) ? $settings['sitemap_orderby'] : 'menu_order';
        $order      = ! empty( $settings['sitemap_order'] ) ? $settings['sitemap_order'] : 'ASC';

        $items_by_type = [];

        foreach ( $post_types as $post_type ) {
            $posts = get_posts( [
                'post_type'      => $post_type,
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => $orderby,
                'order'          => $order,
            ] );

            if ( $posts ) {
                $items_by_type[ $post_type ] = $posts;
            }
        }

        if ( empty( $items_by_type ) ) {
            ?>
            <div class="bdea-loop-grid-empty">No items to display.</div>
            <?php
            return;
        }
        ?>
        <div class="bdea-sitemap">
            <?php foreach ( $items_by_type as $post_type => $posts ) : ?>
                <?php $type_obj = get_post_type_object( $post_type ); ?>
                <div class="bdea-sitemap-group">
                    <h3 class="bdea-sitemap-type-title">
                        <?php echo esc_html( $type_obj ? $type_obj->labels->name : $post_type ); ?>
                        <?php if ( 'yes' === $settings['sitemap_show_count'] ) : ?>
                            <span class="bdea-sitemap-count">(<?php echo esc_html( count( $posts ) ); ?>)</span>
                        <?php endif; ?>
                    </h3>
                    <ul class="bdea-sitemap-list">
                        <?php foreach ( $posts as $post ) : ?>
                            <li><a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
