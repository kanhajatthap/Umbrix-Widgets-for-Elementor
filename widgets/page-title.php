<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Page_Title_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_page_title';
    }

    public function get_title() {
        return 'Page Title';
    }

    public function get_icon() {
        return 'eicon-page-title';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_page_title_section',
            [
                'label' => __( 'Page Title', 'elementskey' ),
            ]
        );

        $this->add_control(
            'page_title_tag',
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

        $this->add_control(
            'page_title_link',
            [
                'label' => __( 'Link to Page', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_page_title_style',
            [
                'label' => __( 'Title', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'page_title_typography',
                'selector' => '{{WRAPPER}} .elementskey-page-title',
            ]
        );

        $this->add_control(
            'page_title_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-page-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementskey-page-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'page_title_align',
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
                    '{{WRAPPER}} .elementskey-page-title' => 'text-align: {{VALUE}};',
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
            echo '<div class="elementskey-loop-grid-empty">No page title found.</div>';
            return;
        }

        $link = get_permalink();
        ?>
        <<?php echo esc_attr( $tag ); ?> class="elementskey-page-title">
            <?php if ( 'yes' === $settings['page_title_link'] && $link ) : ?>
                <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
            <?php else : ?>
                <?php echo esc_html( $title ); ?>
            <?php endif; ?>
        </<?php echo esc_attr( $tag ); ?>>
        <?php
    }
}
