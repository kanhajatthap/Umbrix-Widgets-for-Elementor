<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Site_Title_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_site_title';
    }

    public function get_title() {
        return 'Site Title';
    }

    public function get_icon() {
        return 'eicon-site-title';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_site_title_section',
            [
                'label' => __( 'Site Title', 'elementstack-elementor-addons' ),
            ]
        );

        $this->add_control(
            'site_title_tag',
            [
                'label' => __( 'HTML Tag', 'elementstack-elementor-addons' ),
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
            'site_title_link',
            [
                'label' => __( 'Link to Homepage', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_site_title_style',
            [
                'label' => __( 'Title', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'site_title_typography',
                'selector' => '{{WRAPPER}} .bdea-site-title',
            ]
        );

        $this->add_control(
            'site_title_color',
            [
                'label' => __( 'Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-site-title' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bdea-site-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'site_title_align',
            [
                'label' => __( 'Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bdea-site-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $tag = ! empty( $settings['site_title_tag'] ) ? $settings['site_title_tag'] : 'h1';
        if ( ! in_array( $tag, [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div' ], true ) ) {
            $tag = 'h1';
        }

        $title = get_bloginfo( 'name' );
        ?>
        <<?php echo esc_attr( $tag ); ?> class="bdea-site-title">
            <?php if ( 'yes' === $settings['site_title_link'] ) : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( $title ); ?></a>
            <?php else : ?>
                <?php echo esc_html( $title ); ?>
            <?php endif; ?>
        </<?php echo esc_attr( $tag ); ?>>
        <?php
    }
}
