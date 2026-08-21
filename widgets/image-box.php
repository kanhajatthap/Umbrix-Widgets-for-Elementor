<?php
/**
 * ElementKey Lite
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Image_Box_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_image_box';
    }

    public function get_title() {
        return 'Image Box';
    }

    public function get_icon() {
        return 'eicon-image-box';
    }

    public function get_categories() {
        return [ 'elementkey-lite-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_image_box_section',
            [
                'label' => __( 'Image Box', 'elementkey-lite' ),
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __( 'Image', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => __( 'Image Size', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'medium',
                'options' => [
                    'thumbnail' => __( 'Thumbnail', 'elementkey-lite' ),
                    'medium' => __( 'Medium', 'elementkey-lite' ),
                    'large' => __( 'Large', 'elementkey-lite' ),
                    'medium_large' => __( 'Medium Large', 'elementkey-lite' ),
                    'full' => __( 'Full', 'elementkey-lite' ),
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Feature Title', 'elementkey-lite' ),
                'placeholder' => __( 'Enter title', 'elementkey-lite' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Describe the feature or benefit in a couple of lines.',
                'rows' => 4,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link (optional)', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __( 'Alignment', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementkey-lite' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementkey-lite' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementkey-lite' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-box' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_box_card_style',
            [
                'label' => __( 'Card', 'elementkey-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'card_bg',
            [
                'label' => __( 'Background', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'selector' => '{{WRAPPER}} .bdea-image-box',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_box_image_style',
            [
                'label' => __( 'Image', 'elementkey-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Image Width', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [ 'px' => [ 'min' => 50, 'max' => 1200 ], '%' => [ 'min' => 10, 'max' => 100 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-box-figure img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Image Height', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 50, 'max' => 700 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-box-figure img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_radius',
            [
                'label' => __( 'Border Radius', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-box-figure img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .bdea-image-box-figure img',
            ]
        );

        $this->add_responsive_control(
            'image_spacing',
            [
                'label' => __( 'Spacing Below Image', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 16, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-box-figure' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_image_box_content_style',
            [
                'label' => __( 'Content', 'elementkey-lite' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .bdea-image-box-title',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-box-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .bdea-image-box-desc',
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Description Color', 'elementkey-lite' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-image-box-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $title     = $settings['title'];
        $desc      = $settings['description'];
        $has_link  = ! empty( $settings['link']['url'] );
        $image_id  = ! empty( $settings['image']['id'] ) ? $settings['image']['id'] : '';
        $image_url = ! empty( $settings['image']['url'] ) ? $settings['image']['url'] : '';
        $size      = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'medium';

        $figure_attrs = [ 'class' => 'bdea-image-box-figure' ];

        if ( $has_link ) {
            $this->add_render_attribute( 'link', 'href', $settings['link']['url'] );
            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', '_blank' );
            }
            if ( ! empty( $settings['link']['nofollow'] ) ) {
                $this->add_render_attribute( 'link', 'rel', 'nofollow' );
            }
        }
        ?>
        <div class="bdea-image-box">
            <?php if ( $image_url || $image_id ) : ?>
                <figure <?php echo implode( ' ', array_map( function ( $k, $v ) { return $k . '="' . esc_attr( $v ) . '"'; }, array_keys( $figure_attrs ), $figure_attrs ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attribute values escaped with esc_attr(). ?>>
                    <?php
                    if ( $image_id ) {
                        echo wp_get_attachment_image( $image_id, $size, false, [ 'loading' => 'lazy' ] );
                    } elseif ( $image_url ) {
                        echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $title ) . '" loading="lazy" />';
                    }
                    ?>
                </figure>
            <?php endif; ?>

            <div class="bdea-image-box-body">
                <?php if ( $title ) : ?>
                    <h3 class="bdea-image-box-title"><?php echo esc_html( $title ); ?></h3>
                <?php endif; ?>

                <?php if ( $desc ) : ?>
                    <p class="bdea-image-box-desc"><?php echo esc_html( $desc ); ?></p>
                <?php endif; ?>

                <?php if ( $has_link ) : ?>
                    <a class="bdea-image-box-link" <?php echo $this->get_render_attribute_string( 'link' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor render attributes are escaped internally. ?>>Learn More &#8594;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
