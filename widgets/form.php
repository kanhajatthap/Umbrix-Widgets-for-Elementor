<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Form_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_form';
    }

    public function get_title() {
        return 'Form';
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    public function get_script_depends() {
        return [ 'bdea-content-script' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_form_section',
            [
                'label' => 'Form',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'field_type',
            [
                'label' => 'Field Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => 'Text',
                    'email' => 'Email',
                    'textarea' => 'Textarea',
                    'select' => 'Select',
                ],
            ]
        );

        $repeater->add_control(
            'field_label',
            [
                'label' => 'Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Field Label',
            ]
        );

        $repeater->add_control(
            'field_name',
            [
                'label' => 'Name',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'field_name',
            ]
        );

        $repeater->add_control(
            'field_options',
            [
                'label' => 'Options',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'description' => 'Comma separated list of options.',
                'condition' => [ 'field_type' => 'select' ],
            ]
        );

        $repeater->add_control(
            'field_required',
            [
                'label' => 'Required',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => '',
            ]
        );

        $this->add_control(
            'form_fields',
            [
                'label' => 'Fields',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'field_type' => 'text', 'field_label' => 'Name', 'field_name' => 'name', 'field_required' => 'yes' ],
                    [ 'field_type' => 'email', 'field_label' => 'Email', 'field_name' => 'email', 'field_required' => 'yes' ],
                    [ 'field_type' => 'textarea', 'field_label' => 'Message', 'field_name' => 'message' ],
                ],
                'title_field' => '{{{ field_label }}}',
            ]
        );

        $this->add_control(
            'submit_label',
            [
                'label' => 'Submit Button Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Submit',
            ]
        );

        $this->add_control(
            'email_to',
            [
                'label' => 'Email To',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => get_option( 'admin_email' ),
                'input_type' => 'email',
            ]
        );

        $this->add_control(
            'email_subject',
            [
                'label' => 'Email Subject',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'New Form Submission',
            ]
        );

        $this->add_control(
            'success_message',
            [
                'label' => 'Success Message',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Thank you! Your message has been sent.',
            ]
        );

        $this->add_control(
            'error_message',
            [
                'label' => 'Error Message',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Sorry, your message could not be sent. Please try again.',
            ]
        );

        $this->end_controls_section();

        // Labels Style
        $this->start_controls_section(
            'bdea_form_label_style',
            [
                'label' => 'Labels',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .bdea-form-label',
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => 'Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'label_spacing',
            [
                'label' => 'Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Inputs Style
        $this->start_controls_section(
            'bdea_form_input_style',
            [
                'label' => 'Inputs',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'selector' => '{{WRAPPER}} .bdea-form-control',
            ]
        );

        $this->add_control(
            'input_text_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-control' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label' => 'Placeholder Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-control::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_bg_color',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-control' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_border_color',
            [
                'label' => 'Border Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d1d5db',
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-control' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'input_focus_border_color',
            [
                'label' => 'Focus Border Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-control:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_border_width',
            [
                'label' => 'Border Width',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-control' => 'border-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_border_radius',
            [
                'label' => 'Border Radius',
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
                    '{{WRAPPER}} .bdea-form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'input_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 12,
                    'right' => 14,
                    'bottom' => 12,
                    'left' => 14,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'field_spacing',
            [
                'label' => 'Field Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-field + .bdea-form-field' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'bdea_form_button_style',
            [
                'label' => 'Button',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .bdea-form-submit',
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-submit' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => 'Hover Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-submit:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => 'Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-submit' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_text',
            [
                'label' => 'Hover Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-submit:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_radius',
            [
                'label' => 'Border Radius',
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
                    '{{WRAPPER}} .bdea-form-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 14,
                    'right' => 28,
                    'bottom' => 14,
                    'left' => 28,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_spacing',
            [
                'label' => 'Top Spacing',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 40 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-form-submit' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $fields = $settings['form_fields'];
        if ( empty( $fields ) ) {
            return;
        }

        $submit_label  = ! empty( $settings['submit_label'] ) ? $settings['submit_label'] : 'Submit';
        $success_msg   = ! empty( $settings['success_message'] ) ? $settings['success_message'] : 'Thank you! Your message has been sent.';
        $error_msg     = ! empty( $settings['error_message'] ) ? $settings['error_message'] : 'Sorry, your message could not be sent. Please try again.';
        $email_to      = ! empty( $settings['email_to'] ) ? $settings['email_to'] : get_option( 'admin_email' );
        $email_subject = ! empty( $settings['email_subject'] ) ? $settings['email_subject'] : 'New Form Submission';
        $form_url      = admin_url( 'admin-post.php' );
        $widget_id     = $this->get_id();
        ?>
        <form class="bdea-form" action="<?php echo esc_url( $form_url ); ?>" method="post" data-success="<?php echo esc_attr( $success_msg ); ?>" data-error="<?php echo esc_attr( $error_msg ); ?>" data-email-to="<?php echo esc_attr( $email_to ); ?>" data-email-subject="<?php echo esc_attr( $email_subject ); ?>">
            <input type="hidden" name="action" value="bdea_form_submit">
            <input type="hidden" name="bdea_form_id" value="<?php echo esc_attr( $widget_id ); ?>">
            <?php foreach ( $fields as $index => $field ) : ?>
                <?php
                $field_type   = ! empty( $field['field_type'] ) ? $field['field_type'] : 'text';
                $label        = ! empty( $field['field_label'] ) ? $field['field_label'] : '';
                $name         = ! empty( $field['field_name'] ) ? $field['field_name'] : 'field_' . $index;
                $required     = ( 'yes' === $field['field_required'] );
                $input_name   = 'bdea_field_' . $name;
                $field_id     = 'bdea-form-' . $widget_id . '-' . $index;
                $required_attr = $required ? ' required' : '';
                ?>
                <div class="bdea-form-field bdea-form-field-<?php echo esc_attr( $field_type ); ?>">
                    <?php if ( $label ) : ?>
                        <label class="bdea-form-label" for="<?php echo esc_attr( $field_id ); ?>">
                            <?php echo esc_html( $label ); ?><?php echo $required ? ' *' : ''; ?>
                        </label>
                    <?php endif; ?>

                    <?php if ( 'textarea' === $field_type ) : ?>
                        <textarea class="bdea-form-control" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $input_name ); ?>" rows="4"<?php echo $required_attr; ?>></textarea>
                    <?php elseif ( 'select' === $field_type ) : ?>
                        <select class="bdea-form-control" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $input_name ); ?>"<?php echo $required_attr; ?>>
                            <option value="">Select option</option>
                            <?php
                            $options = ! empty( $field['field_options'] ) ? array_map( 'trim', explode( ',', $field['field_options'] ) ) : [];
                            foreach ( $options as $option ) :
                                ?>
                                <option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else : ?>
                        <input class="bdea-form-control" type="<?php echo esc_attr( $field_type ); ?>" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $input_name ); ?>"<?php echo $required_attr; ?>>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <?php wp_nonce_field( 'bdea_form_submit', 'bdea_form_nonce' ); ?>

            <div class="bdea-form-message"></div>

            <button type="submit" class="bdea-form-submit"><?php echo esc_html( $submit_label ); ?></button>
        </form>
        <?php
    }
}