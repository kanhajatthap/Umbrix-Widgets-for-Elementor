<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Breadcrumbs_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_breadcrumbs';
    }

    public function get_title() {
        return 'Breadcrumbs';
    }

    public function get_icon() {
        return 'eicon-product-breadcrumbs';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_breadcrumbs_section',
            [
                'label' => 'Breadcrumbs',
            ]
        );

        $this->add_control(
            'breadcrumb_separator',
            [
                'label' => 'Separator',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '/',
            ]
        );

        $this->add_control(
            'breadcrumb_home_label',
            [
                'label' => 'Home Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Home',
            ]
        );

        $this->add_control(
            'breadcrumb_hide_on_front',
            [
                'label' => 'Hide on Homepage',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'bdea_breadcrumbs_typo',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumb_typography',
                'selector' => '{{WRAPPER}} .bdea-breadcrumbs',
            ]
        );

        $this->add_responsive_control(
            'breadcrumb_align',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => 'Left', 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => 'Center', 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => 'Right', 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-breadcrumbs' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'breadcrumb_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-breadcrumbs a' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .bdea-breadcrumb-separator' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Colors
        $this->start_controls_section(
            'bdea_breadcrumbs_colors',
            [
                'label' => 'Colors',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'breadcrumb_color',
            [
                'label' => 'Link Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-breadcrumbs a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'breadcrumb_hover_color',
            [
                'label' => 'Link Hover Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-breadcrumbs a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'breadcrumb_current_color',
            [
                'label' => 'Current Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-breadcrumbs-current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'breadcrumb_sep_color',
            [
                'label' => 'Separator Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#9ca3af',
                'selectors' => [
                    '{{WRAPPER}} .bdea-breadcrumb-separator' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function build_trail( $settings ) {
        $items = [];

        $home_label = ! empty( $settings['breadcrumb_home_label'] ) ? $settings['breadcrumb_home_label'] : 'Home';
        $items[] = [ 'label' => $home_label, 'url' => home_url( '/' ) ];

        if ( is_home() ) {
            $items[] = [ 'label' => get_the_title( get_option( 'page_for_posts' ) ), 'url' => '' ];
        } elseif ( is_singular() ) {
            $post_id = get_the_ID();

            $post_type = get_post_type( $post_id );
            $type_obj = get_post_type_object( $post_type );

            if ( $type_obj && $type_obj->has_archive ) {
                $archive_link = get_post_type_archive_link( $post_type );
                if ( $archive_link ) {
                    $items[] = [ 'label' => $type_obj->labels->name, 'url' => $archive_link ];
                }
            }

            if ( 'post' === $post_type ) {
                $categories = get_the_category( $post_id );
                if ( ! empty( $categories ) ) {
                    $cat = $categories[0];
                    $items[] = [ 'label' => $cat->name, 'url' => get_category_link( $cat->term_id ) ];
                }
            }

            $ancestors = get_post_ancestors( $post_id );
            foreach ( array_reverse( $ancestors ) as $ancestor_id ) {
                $items[] = [ 'label' => get_the_title( $ancestor_id ), 'url' => get_permalink( $ancestor_id ) ];
            }

            $items[] = [ 'label' => get_the_title( $post_id ), 'url' => '' ];
        } elseif ( is_archive() ) {
            $items[] = [ 'label' => wp_strip_all_tags( get_the_archive_title() ), 'url' => '' ];
        } elseif ( is_search() ) {
            $items[] = [ 'label' => sprintf( 'Search: %s', get_search_query() ), 'url' => '' ];
        } elseif ( is_404() ) {
            $items[] = [ 'label' => '404', 'url' => '' ];
        }

        return $items;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( is_front_page() && 'yes' === $settings['breadcrumb_hide_on_front'] ) {
            return;
        }

        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div class="bdea-breadcrumbs">', '</div>' );
            return;
        }

        $items = $this->build_trail( $settings );
        $sep   = ! empty( $settings['breadcrumb_separator'] ) ? $settings['breadcrumb_separator'] : '/';
        ?>
        <nav class="bdea-breadcrumbs" aria-label="Breadcrumb">
            <?php foreach ( $items as $index => $item ) : ?>
                <?php if ( $index > 0 ) : ?>
                    <span class="bdea-breadcrumb-separator" aria-hidden="true"><?php echo esc_html( $sep ); ?></span>
                <?php endif; ?>
                <?php if ( ! empty( $item['url'] ) && $index < count( $items ) - 1 ) : ?>
                    <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                <?php else : ?>
                    <span class="bdea-breadcrumbs-current"><?php echo esc_html( $item['label'] ); ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <?php
    }
}