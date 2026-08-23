<?php
/**
 * ElementsKey Addons for Elementor
 * GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class ELEMENTSKEY_Link_In_Bio_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'elementskey_link_in_bio';
    }

    public function get_title() {
        return 'Link in Bio';
    }

    public function get_icon() {
        return 'eicon-link';
    }

    public function get_categories() {
        return [ 'elementskey-elements' ];
    }

    public function get_style_depends() {
        return [ 'elementskey-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'elementskey_link_in_bio_section',
            [
                'label' => __( 'Link in Bio', 'elementskey' ),
            ]
        );

        $this->add_control(
            'bio_avatar',
            [
                'label' => __( 'Avatar', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'bio_name',
            [
                'label' => __( 'Name', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Jane Doe', 'elementskey' ),
            ]
        );

        $this->add_control(
            'bio_handle',
            [
                'label' => __( 'Handle / Subtitle', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '@janedoe',
            ]
        );

        $this->add_control(
            'bio_text',
            [
                'label' => __( 'Bio', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Welcome to my little corner of the internet.',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'bio_link_label',
            [
                'label' => __( 'Label', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'My Link', 'elementskey' ),
            ]
        );

        $repeater->add_control(
            'bio_link_url',
            [
                'label' => __( 'URL', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $repeater->add_control(
            'bio_link_icon',
            [
                'label' => __( 'Icon', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-link',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'bio_link_bg',
            [
                'label' => __( 'Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
            ]
        );

        $this->add_control(
            'bio_links',
            [
                'label' => __( 'Links', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'bio_link_label' => __( 'My Website', 'elementskey' ), 'bio_link_url' => [ 'url' => 'https://example.com' ] ],
                    [ 'bio_link_label' => __( 'My Portfolio', 'elementskey' ), 'bio_link_url' => [ 'url' => 'https://example.com/portfolio' ] ],
                    [ 'bio_link_label' => __( 'Contact Me', 'elementskey' ), 'bio_link_url' => [ 'url' => 'https://example.com/contact' ] ],
                ],
                'title_field' => '{{{ bio_link_label }}}',
            ]
        );

        $this->add_control(
            'bio_social_icons',
            [
                'label' => __( 'Show Social Icons Row', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'elementskey_link_in_bio_style',
            [
                'label' => __( 'Style', 'elementskey' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bio_page_bg',
            [
                'label' => __( 'Page Background', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bio_name_color',
            [
                'label' => __( 'Name Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bio_text_color',
            [
                'label' => __( 'Bio Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bio_link_color',
            [
                'label' => __( 'Link Text Color', 'elementskey' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .elementskey-bio-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $avatar   = ! empty( $settings['bio_avatar']['url'] ) ? $settings['bio_avatar']['url'] : '';
        ?>
        <div class="elementskey-bio-widget">
            <?php if ( $avatar ) : ?>
                <div class="elementskey-bio-avatar">
                    <img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $settings['bio_name'] ); ?>" loading="lazy" />
                </div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_name'] ) ) : ?>
                <div class="elementskey-bio-name"><?php echo esc_html( $settings['bio_name'] ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_handle'] ) ) : ?>
                <div class="elementskey-bio-handle"><?php echo esc_html( $settings['bio_handle'] ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_text'] ) ) : ?>
                <p class="elementskey-bio-text"><?php echo esc_html( $settings['bio_text'] ); ?></p>
            <?php endif; ?>
            <div class="elementskey-bio-links">
                <?php if ( ! empty( $settings['bio_links'] ) ) : ?>
                    <?php foreach ( $settings['bio_links'] as $link ) : ?>
                        <a class="elementskey-bio-link"
                           href="<?php echo esc_url( ! empty( $link['bio_link_url']['url'] ) ? $link['bio_link_url']['url'] : '#' ); ?>"
                           <?php echo ! empty( $link['bio_link_url']['is_external'] ) ? 'target="_blank" rel="noopener"' : ''; ?>
                           style="background-color: <?php echo esc_attr( ! empty( $link['bio_link_bg'] ) ? $link['bio_link_bg'] : '#4361ee' ); ?>;">
                            <?php if ( ! empty( $link['bio_link_icon']['value'] ) ) : ?>
                                <span class="elementskey-bio-link-icon"><?php \Elementor\Icons_Manager::render_icon( $link['bio_link_icon'], [ 'aria-hidden' => 'true' ] ); ?></span>
                            <?php endif; ?>
                            <span><?php echo esc_html( $link['bio_link_label'] ); ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
