<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Page_Title_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_page_title';
    }

    public function get_title() {
        return 'Page Title';
    }

    public function get_icon() {
        return 'eicon-page-title';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_page_title_section',
            [
                'label' => 'Page Title',
            ]
        );

        $this->add_control(
            'page_title_tag',
            [
                'label' => 'HTML Tag',
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

        $this->add_control(
            'page_title_link',
            [
                'label' => 'Link to Page',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_page_title_style',
            [
                'label' => 'Title',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'page_title_typography',
                'selector' => '{{WRAPPER}} .bdea-page-title',
            ]
        );

        $this->add_control(
            'page_title_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-page-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-page-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'page_title_align',
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
                    '{{WRAPPER}} .bdea-page-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $tag = ! empty( $settings['page_title_tag'] ) ? $settings['page_title_tag'] : 'h1';
        if ( ! in_array( $tag, [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div' ], true ) ) {
            $tag = 'h1';
        }

        $title = get_the_title();

        if ( empty( $title ) ) {
            $queried = get_queried_object();
            if ( $queried instanceof \WP_Post ) {
                $title = $queried->post_title;
            } elseif ( is_archive() ) {
                $title = get_the_archive_title();
            }
        }

        if ( empty( $title ) ) {
            echo '<div class="bdea-loop-grid-empty">No page title found.</div>';
            return;
        }

        $link = get_permalink();
        ?>
        <<?php echo esc_attr( $tag ); ?> class="bdea-page-title">
            <?php if ( 'yes' === $settings['page_title_link'] && $link ) : ?>
                <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
            <?php else : ?>
                <?php echo esc_html( $title ); ?>
            <?php endif; ?>
        </<?php echo esc_attr( $tag ); ?>>
        <?php
    }
}
