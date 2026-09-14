<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Wp_Menu_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_wp_menu';
    }

    public function get_title() {
        return 'WordPress Menu';
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    public function get_script_depends() {
        return [ 'elementskey-content-script' ];
    }

    protected function get_menus() {
        $menus = wp_get_nav_menus();
        $options = [];
        foreach ( $menus as $menu ) {
            $options[ $menu->term_id ] = $menu->name;
        }
        return $options;
    }

    protected function get_locations() {
        return get_registered_nav_menus();
    }

    protected function get_mobile_breakpoint() {
        $breakpoint = 767;

        if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->breakpoints ) ) {
            $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

            if ( ! empty( $breakpoints['mobile'] ) ) {
                $value = (int) $breakpoints['mobile']->get_value();

                if ( $value > 0 ) {
                    $breakpoint = $value;
                }
            }
        }

        return $breakpoint;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_wp_menu_section',
            [
                'label' => __( 'WordPress Menu', 'elementskey' ),
            ]
        );

        $this->add_control(
            'wp_menu_source',
            [
                'label' => __( 'Source', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'menu',
                'options' => [
                    'menu' => __( 'Specific Menu', 'elementskey' ),
                    'location' => __( 'Theme Location', 'elementskey' ),
                ],
            ]
        );

        $this->add_control(
            'wp_menu_id',
            [
                'label' => __( 'Select Menu', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_menus(),
                'condition' => [ 'wp_menu_source' => 'menu' ],
            ]
        );

        $this->add_control(
            'wp_menu_location',
            [
                'label' => __( 'Theme Location', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_locations(),
                'condition' => [ 'wp_menu_source' => 'location' ],
            ]
        );

        $this->add_control(
            'wp_menu_style',
            [
                'label' => __( 'Design Style', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default', 'elementskey' ),
                    'pills' => __( 'Pills', 'elementskey' ),
                    'underline' => __( 'Underline', 'elementskey' ),
                    'vertical' => __( 'Vertical', 'elementskey' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_align',
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
                    '{{WRAPPER}} .elementskey-wp-menu' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'wp_menu_show_caret',
            [
                'label' => __( 'Dropdown Indicator', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'elementskey' ),
                'label_off' => __( 'Hide', 'elementskey' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __( 'Show the dropdown arrow next to menu items that have a submenu.', 'elementskey' ),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_wp_menu_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'wp_menu_typography',
                'selector' => '{{WRAPPER}} .elementskey-wp-menu a',
            ]
        );

        $this->add_control(
            'wp_menu_color',
            [
                'label' => __( 'Link Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-wp-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'wp_menu_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-wp-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* Style: Submenu Indicator */
        $this->start_controls_section(
            'elementskey_wp_menu_caret_style',
            [
                'label' => __( 'Submenu Indicator', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wp_menu_caret_color',
            [
                'label' => __( 'Icon Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu-caret' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_caret_size',
            [
                'label' => __( 'Icon Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu-caret' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_caret_thickness',
            [
                'label' => __( 'Icon Thickness', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 6 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 2 ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu-caret' => 'border-right-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_caret_gap',
            [
                'label' => __( 'Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget .elementskey-menu-caret' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'wp_menu_mobile_caret_heading',
            [
                'label' => __( 'Mobile', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wp_menu_mobile_caret_color',
            [
                'label' => __( 'Icon Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu-caret' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_mobile_caret_size',
            [
                'label' => __( 'Icon Size', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu-caret' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_mobile_caret_thickness',
            [
                'label' => __( 'Icon Thickness', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 1, 'max' => 6 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 2 ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu-caret' => 'border-right-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wp_menu_mobile_caret_gap',
            [
                'label' => __( 'Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 30 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-menu-widget.is-mobile .elementskey-menu-caret' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'wp_menu_mobile_caret_position',
            [
                'label' => __( 'Indicator Position', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'inline',
                'description' => __( 'Place the dropdown indicator next to the text or push it to the right edge of the menu item.', 'elementskey' ),
                'options' => [
                    'inline' => __( 'Inline (Next to Text)', 'elementskey' ),
                    'end' => __( 'Right (Text Left)', 'elementskey' ),
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = [
            'container'     => false,
            'menu_class'    => 'elementskey-wp-menu',
            'fallback_cb'   => false,
            'depth'         => 3,
        ];

        if ( 'location' === $settings['wp_menu_source'] ) {
            $args['theme_location'] = ! empty( $settings['wp_menu_location'] ) ? $settings['wp_menu_location'] : '';
            $args['menu']           = 0;
        } else {
            $args['menu'] = ! empty( $settings['wp_menu_id'] ) ? absint( $settings['wp_menu_id'] ) : 0;
        }

        if ( empty( $args['menu'] ) && empty( $args['theme_location'] ) ) {
            ?>
            <div class="elementskey-loop-grid-empty">Select a menu.</div>
            <?php
            return;
        }

        $style_class = 'default' !== $settings['wp_menu_style'] ? ' elementskey-menu-style-' . $settings['wp_menu_style'] : '';
        $args['menu_class'] .= $style_class;

        $element_id = $this->get_id();
        $breakpoint = $this->get_mobile_breakpoint();
        $caret_pos  = ! empty( $settings['wp_menu_mobile_caret_position'] ) ? $settings['wp_menu_mobile_caret_position'] : 'inline';

        $this->add_render_attribute(
            'elementskey-menu-wrapper',
            [
                'class'              => 'elementskey-menu-widget' . ( 'end' === $caret_pos ? ' is-caret-end' : '' ),
                'data-menu-breakpoint' => $breakpoint,
                'style'              => '--elementskey-menu-breakpoint: ' . $breakpoint . 'px;',
            ]
        );

        $args['menu_id']     = 'elementskey-wp-menu-' . $element_id;

        if ( ! empty( $settings['wp_menu_show_caret'] ) && 'yes' === $settings['wp_menu_show_caret'] ) {
            $args['link_after'] = '<span class="elementskey-menu-caret" aria-hidden="true"></span>';
        }
        ?>
        <nav <?php $this->print_render_attribute_string( 'elementskey-menu-wrapper' ); ?> aria-label="<?php esc_attr_e( 'Menu', 'elementskey' ); ?>">
            <button class="elementskey-menu-toggle" type="button" aria-label="<?php esc_attr_e( 'Toggle menu', 'elementskey' ); ?>" aria-expanded="false" aria-controls="elementskey-menu-container-<?php echo esc_attr( $element_id ); ?>">
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
                <span class="elementskey-menu-toggle-bar" aria-hidden="true"></span>
            </button>
            <div class="elementskey-menu-container" id="elementskey-menu-container-<?php echo esc_attr( $element_id ); ?>">
                <?php wp_nav_menu( $args ); ?>
            </div>
        </nav>
        <?php
    }
}
