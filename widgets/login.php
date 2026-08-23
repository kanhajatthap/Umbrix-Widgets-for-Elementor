<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Login_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_login';
    }

    public function get_title() {
        return 'Login';
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_login_section',
            [
                'label' => __( 'Login', 'elementskey' ),
            ]
        );

        $this->add_control(
            'login_title',
            [
                'label' => __( 'Title', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Login', 'elementskey' ),
            ]
        );

        $this->add_control(
            'show_remember',
            [
                'label' => __( 'Show Remember Me', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'login_redirect',
            [
                'label' => __( 'Redirect URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'https://example.com/',
            ]
        );

        $this->end_controls_section();

        // Title Style
        $this->start_controls_section(
            'elementskey_login_title_style',
            [
                'label' => __( 'Title', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'login_title_typography',
                'selector' => '{{WRAPPER}} .elementskey-login-title',
            ]
        );

        $this->add_control(
            'login_title_color',
            [
                'label' => __( 'Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'login_title_spacing',
            [
                'label' => __( 'Spacing', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Form Style
        $this->start_controls_section(
            'elementskey_login_form_style',
            [
                'label' => __( 'Form', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'form_spacing',
            [
                'label' => __( 'Form Gap', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 50 ] ],
                'default' => [ 'size' => 12, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-widget .elementskey-login-form-wrap .elementskey-login-form div' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Input Style
        $this->start_controls_section(
            'elementskey_login_input_style',
            [
                'label' => __( 'Input Fields', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .elementskey-login-form input[type="text"], {{WRAPPER}} .elementskey-login-form input[type="password"], {{WRAPPER}} .elementskey-login-form input[type="email"]',
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="text"], {{WRAPPER}} .elementskey-login-form input[type="password"], {{WRAPPER}} .elementskey-login-form input[type="email"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="text"], {{WRAPPER}} .elementskey-login-form input[type="password"], {{WRAPPER}} .elementskey-login-form input[type="email"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => __( 'Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="text"], {{WRAPPER}} .elementskey-login-form input[type="password"], {{WRAPPER}} .elementskey-login-form input[type="email"]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_border_width',
            [
                'label' => __( 'Border Width', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
                'default' => [ 'size' => 1, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="text"], {{WRAPPER}} .elementskey-login-form input[type="password"], {{WRAPPER}} .elementskey-login-form input[type="email"]' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 4,
                    'right' => 4,
                    'bottom' => 4,
                    'left' => 4,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="text"], {{WRAPPER}} .elementskey-login-form input[type="password"], {{WRAPPER}} .elementskey-login-form input[type="email"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 10,
                    'right' => 14,
                    'bottom' => 10,
                    'left' => 14,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="text"], {{WRAPPER}} .elementskey-login-form input[type="password"], {{WRAPPER}} .elementskey-login-form input[type="email"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'elementskey_login_button_style',
            [
                'label' => __( 'Button', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typography',
                'selector' => '{{WRAPPER}} .elementskey-login-form input[type="submit"]',
            ]
        );

        $this->add_control(
            'btn_text_color',
            [
                'label' => __( 'Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_bg_color',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_bg',
            [
                'label' => __( 'Hover Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="submit"]:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btn_hover_text',
            [
                'label' => __( 'Hover Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="submit"]:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_border_radius',
            [
                'label' => __( 'Border Radius', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 6,
                    'right' => 6,
                    'bottom' => 6,
                    'left' => 6,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btn_padding',
            [
                'label' => __( 'Padding', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 12,
                    'right' => 24,
                    'bottom' => 12,
                    'left' => 24,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btn_border_color',
            [
                'label' => __( 'Border Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form input[type="submit"]' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Link Style
        $this->start_controls_section(
            'elementskey_login_link_style',
            [
                'label' => __( 'Links', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label' => __( 'Link Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_hover_color',
            [
                'label' => __( 'Hover Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementskey-login-form a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $title    = ! empty( $settings['login_title'] ) ? $settings['login_title'] : '';
        $remember = ( 'yes' === $settings['show_remember'] );
        $redirect = ! empty( $settings['login_redirect'] ) ? $settings['login_redirect'] : '';
        ?>
        <div class="elementskey-login-widget">
            <?php if ( $title ) : ?>
                <h3 class="elementskey-login-title"><?php echo esc_html( $title ); ?></h3>
            <?php endif; ?>

            <?php if ( is_user_logged_in() ) : ?>
                <?php $current_user = wp_get_current_user(); ?>
                <div class="elementskey-login-logged-in">
                    <p class="elementskey-login-greeting">
                        <?php echo esc_html( sprintf( 'Welcome back, %s!', $current_user->display_name ) ); ?>
                    </p>
                    <a class="elementskey-login-logout" href="<?php echo esc_url( wp_logout_url( $redirect ? $redirect : get_permalink() ) ); ?>">
                        <?php esc_html_e( 'Logout', 'elementskey' ); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="elementskey-login-form-wrap elementskey-login-form">
                    <?php
                    $args = [
                        'echo'           => true,
                        'form_id'        => 'elementskey-login-form',
                        'form_class'     => 'elementskey-login-form',
                        'label_username' => __( 'Username', 'elementskey' ),
                        'label_password' => __( 'Password', 'elementskey' ),
                        'label_remember' => __( 'Remember Me', 'elementskey' ),
                        'label_log_in'   => __( 'Log In', 'elementskey' ),
                        'remember'       => $remember,
                    ];
                    if ( $redirect ) {
                        $args['redirect'] = $redirect;
                    }
                    wp_login_form( $args );
                    ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}