<?php
/**
 * ElementStack Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Basic_Gallery_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_basic_gallery';
    }

    public function get_title() {
        return 'Basic Gallery';
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_gallery_section',
            [
                'label' => __( 'Gallery', 'elementstack-elementor-addons' ),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'gallery_image',
            [
                'label' => __( 'Image', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'gallery_caption',
            [
                'label' => __( 'Caption', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $repeater->add_control(
            'gallery_link',
            [
                'label' => __( 'Link', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $this->add_control(
            'galleries',
            [
                'label' => __( 'Images', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'gallery_caption' => __( 'Gallery Image 1', 'elementstack-elementor-addons' ) ],
                    [ 'gallery_caption' => __( 'Gallery Image 2', 'elementstack-elementor-addons' ) ],
                    [ 'gallery_caption' => __( 'Gallery Image 3', 'elementstack-elementor-addons' ) ],
                ],
                'title_field' => '{{{ gallery_caption }}}',
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => __( 'Columns', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
            ]
        );

        $this->add_responsive_control(
            'gallery_gap',
            [
                'label' => __( 'Gap', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 80 ] ],
                'default' => [ 'size' => 12, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Image Style
        $this->start_controls_section(
            'bdea_gallery_image_style',
            [
                'label' => __( 'Image', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Image Height', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 80, 'max' => 700 ] ],
                'default' => [ 'size' => 220, 'unit' => 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-item img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_fit',
            [
                'label' => __( 'Object Fit', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => __( 'Cover', 'elementstack-elementor-addons' ),
                    'contain' => __( 'Contain', 'elementstack-elementor-addons' ),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-item img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_radius',
            [
                'label' => __( 'Border Radius', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-item img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'selector' => '{{WRAPPER}} .bdea-gallery-item img',
            ]
        );

        $this->end_controls_section();

        // Hover Style
        $this->start_controls_section(
            'bdea_gallery_hover_style',
            [
                'label' => __( 'Hover', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hover_scale',
            [
                'label' => __( 'Hover Scale', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [ '' => [ 'min' => 1, 'max' => 1.5, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-item img' => 'transition: transform 0.3s ease;',
                    '{{WRAPPER}} .bdea-gallery-item:hover img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'hover_opacity',
            [
                'label' => __( 'Hover Opacity', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ '' ],
                'range' => [ '' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.01 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-item img' => 'transition: opacity 0.3s ease;',
                    '{{WRAPPER}} .bdea-gallery-item:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Caption Style
        $this->start_controls_section(
            'bdea_gallery_caption_style',
            [
                'label' => __( 'Caption', 'elementstack-elementor-addons' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'caption_typography',
                'selector' => '{{WRAPPER}} .bdea-gallery-caption',
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label' => __( 'Caption Color', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_align',
            [
                'label' => __( 'Caption Alignment', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [ 'title' => __( 'Left', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-left' ],
                    'center' => [ 'title' => __( 'Center', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-center' ],
                    'right' => [ 'title' => __( 'Right', 'elementstack-elementor-addons' ), 'icon' => 'eicon-text-align-right' ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-caption' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'caption_spacing',
            [
                'label' => __( 'Spacing Above Caption', 'elementstack-elementor-addons' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [ 'px' => [ 'min' => 0, 'max' => 60 ] ],
                'selectors' => [
                    '{{WRAPPER}} .bdea-gallery-caption' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty( $settings['galleries'] ) ) {
            return;
        }

        $columns = ! empty( $settings['columns'] ) ? $settings['columns'] : '3';

        $grid_classes = [ 'bdea-gallery-grid', 'bdea-gallery-columns-' . $columns ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', array_filter( $grid_classes ) ) ); ?>">
            <?php foreach ( $settings['galleries'] as $item ) : ?>
                <?php
                $image_id  = ! empty( $item['gallery_image']['id'] ) ? $item['gallery_image']['id'] : '';
                $image_url = ! empty( $item['gallery_image']['url'] ) ? $item['gallery_image']['url'] : '';
                $caption   = ! empty( $item['gallery_caption'] ) ? $item['gallery_caption'] : '';
                $has_link  = ! empty( $item['gallery_link']['url'] );
                ?>
                <figure class="bdea-gallery-item">
                    <?php if ( $has_link ) : ?>
                        <a href="<?php echo esc_url( $item['gallery_link']['url'] ); ?>"
                           <?php echo ! empty( $item['gallery_link']['is_external'] ) ? 'target="_blank"' : ''; ?>
                           <?php echo ! empty( $item['gallery_link']['nofollow'] ) ? 'rel="nofollow"' : ''; ?>>
                    <?php endif; ?>

                    <?php
                    if ( $image_id ) {
                        echo wp_get_attachment_image( $image_id, 'medium_large', false, [ 'loading' => 'lazy' ] );
                    } elseif ( $image_url ) {
                        echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $caption ) . '" loading="lazy" />';
                    }
                    ?>

                    <?php if ( $has_link ) : ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( $caption ) : ?>
                        <figcaption class="bdea-gallery-caption"><?php echo esc_html( $caption ); ?></figcaption>
                    <?php endif; ?>
                </figure>
            <?php endforeach; ?>
        </div>
        <?php
    }
}