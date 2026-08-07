<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class BDEA_Link_In_Bio_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'bdea_link_in_bio';
    }

    public function get_title() {
        return 'Link in Bio';
    }

    public function get_icon() {
        return 'eicon-link';
    }

    public function get_categories() {
        return [ 'elementstack-elements' ];
    }

    public function get_style_depends() {
        return [ 'bdea-content-style' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'bdea_link_in_bio_section',
            [
                'label' => 'Link in Bio',
            ]
        );

        $this->add_control(
            'bio_avatar',
            [
                'label' => 'Avatar',
                'type' => \Elementor\Controls_Manager::MEDIA,
            ]
        );

        $this->add_control(
            'bio_name',
            [
                'label' => 'Name',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Jane Doe',
            ]
        );

        $this->add_control(
            'bio_handle',
            [
                'label' => 'Handle / Subtitle',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '@janedoe',
            ]
        );

        $this->add_control(
            'bio_text',
            [
                'label' => 'Bio',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'Welcome to my little corner of the internet.',
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'bio_link_label',
            [
                'label' => 'Label',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'My Link',
            ]
        );

        $repeater->add_control(
            'bio_link_url',
            [
                'label' => 'URL',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com',
            ]
        );

        $repeater->add_control(
            'bio_link_icon',
            [
                'label' => 'Icon',
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
                'label' => 'Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4361ee',
            ]
        );

        $this->add_control(
            'bio_links',
            [
                'label' => 'Links',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'bio_link_label' => 'My Website', 'bio_link_url' => [ 'url' => 'https://example.com' ] ],
                    [ 'bio_link_label' => 'My Portfolio', 'bio_link_url' => [ 'url' => 'https://example.com/portfolio' ] ],
                    [ 'bio_link_label' => 'Contact Me', 'bio_link_url' => [ 'url' => 'https://example.com/contact' ] ],
                ],
                'title_field' => '{{{ bio_link_label }}}',
            ]
        );

        $this->add_control(
            'bio_social_icons',
            [
                'label' => 'Show Social Icons Row',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'bdea_link_in_bio_style',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bio_page_bg',
            [
                'label' => 'Page Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f7f7f7',
                'selectors' => [
                    '{{WRAPPER}} .bdea-bio-widget' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bio_name_color',
            [
                'label' => 'Name Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#1f2937',
                'selectors' => [
                    '{{WRAPPER}} .bdea-bio-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bio_text_color',
            [
                'label' => 'Bio Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#4b5563',
                'selectors' => [
                    '{{WRAPPER}} .bdea-bio-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bio_link_color',
            [
                'label' => 'Link Text Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .bdea-bio-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $avatar   = ! empty( $settings['bio_avatar']['url'] ) ? $settings['bio_avatar']['url'] : '';
        ?>
        <div class="bdea-bio-widget">
            <?php if ( $avatar ) : ?>
                <div class="bdea-bio-avatar">
                    <img src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $settings['bio_name'] ); ?>" loading="lazy" />
                </div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_name'] ) ) : ?>
                <div class="bdea-bio-name"><?php echo esc_html( $settings['bio_name'] ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_handle'] ) ) : ?>
                <div class="bdea-bio-handle"><?php echo esc_html( $settings['bio_handle'] ); ?></div>
            <?php endif; ?>
            <?php if ( ! empty( $settings['bio_text'] ) ) : ?>
                <p class="bdea-bio-text"><?php echo esc_html( $settings['bio_text'] ); ?></p>
            <?php endif; ?>
            <div class="bdea-bio-links">
                <?php if ( ! empty( $settings['bio_links'] ) ) : ?>
                    <?php foreach ( $settings['bio_links'] as $link ) : ?>
                        <a class="bdea-bio-link"
                           href="<?php echo esc_url( ! empty( $link['bio_link_url']['url'] ) ? $link['bio_link_url']['url'] : '#' ); ?>"
                           <?php echo ! empty( $link['bio_link_url']['is_external'] ) ? 'target="_blank" rel="noopener"' : ''; ?>
                           style="background-color: <?php echo esc_attr( ! empty( $link['bio_link_bg'] ) ? $link['bio_link_bg'] : '#4361ee' ); ?>;">
                            <?php if ( ! empty( $link['bio_link_icon']['value'] ) ) : ?>
                                <i class="bdea-bio-link-icon <?php echo esc_attr( $link['bio_link_icon']['value'] ); ?>"></i>
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
