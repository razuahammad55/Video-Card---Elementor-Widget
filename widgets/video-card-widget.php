<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class VCE_Video_Card_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'video_card';
    }

    public function get_title() {
        return esc_html__( 'Video Card', 'video-card-elementor' );
    }

    public function get_icon() {
        return 'eicon-youtube';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_keywords() {
        return [ 'video', 'card', 'youtube', 'tiktok', 'instagram', 'media', 'shorts' ];
    }

    protected function register_controls() {

        // ===== CONTENT TAB =====
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__( 'Query Settings', 'video-card-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'video_count',
            [
                'label'       => esc_html__( 'Number of Videos', 'video-card-elementor' ),
                'type'        => \Elementor\Controls_Manager::NUMBER,
                'default'     => -1,
                'min'         => -1,
                'description' => esc_html__( 'Use -1 to show all videos.', 'video-card-elementor' ),
            ]
        );

        $this->add_control(
            'video_group',
            [
                'label'       => esc_html__( 'Video Group', 'video-card-elementor' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__( 'e.g. Home Page', 'video-card-elementor' ),
                'description' => esc_html__( 'Filter by ACF "video_group" field value.', 'video-card-elementor' ),
            ]
        );

        $this->add_control(
            'video_ids',
            [
                'label'       => esc_html__( 'Specific Video IDs', 'video-card-elementor' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__( '10,15,22', 'video-card-elementor' ),
                'description' => esc_html__( 'Comma-separated post IDs. Overrides count ordering.', 'video-card-elementor' ),
            ]
        );

        $this->end_controls_section();

        // ===== STYLE TAB — Card =====
        $this->start_controls_section(
            'section_style_card',
            [
                'label' => esc_html__( 'Card Style', 'video-card-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'branding_color',
            [
                'label'     => esc_html__( 'Branding / Accent Color', 'video-card-elementor' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#8bc34a',
                'selectors' => [
                    '{{WRAPPER}} .vc-wrapper' => '--branding-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_radius',
            [
                'label'      => esc_html__( 'Card Border Radius', 'video-card-elementor' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 40 ],
                ],
                'default'    => [ 'size' => 16, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .vc-card' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'card_border_color',
            [
                'label'     => esc_html__( 'Card Border Color', 'video-card-elementor' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#d7d7d7',
                'selectors' => [
                    '{{WRAPPER}} .vc-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===== STYLE TAB — Typography =====
        $this->start_controls_section(
            'section_style_typography',
            [
                'label' => esc_html__( 'Typography', 'video-card-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'username_color',
            [
                'label'     => esc_html__( 'Username Color', 'video-card-elementor' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .vc-username' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label'     => esc_html__( 'Caption Color', 'video-card-elementor' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .vc-caption' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ===== STYLE TAB — Grid Layout =====
        $this->start_controls_section(
            'section_style_grid',
            [
                'label' => esc_html__( 'Grid Layout', 'video-card-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'          => esc_html__( 'Columns', 'video-card-elementor' ),
                'type'           => \Elementor\Controls_Manager::SELECT,
                'default'        => '4',
                'tablet_default' => '3',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vc-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
                'description' => esc_html__( 'Mobile defaults to horizontal scroll carousel.', 'video-card-elementor' ),
            ]
        );

        $this->add_control(
            'grid_gap',
            [
                'label'      => esc_html__( 'Gap', 'video-card-elementor' ),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                    'px' => [ 'min' => 0, 'max' => 60 ],
                ],
                'default'    => [ 'size' => 24, 'unit' => 'px' ],
                'selectors'  => [
                    '{{WRAPPER}} .vc-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $count = intval( $settings['video_count'] ?? -1 );
        $group = sanitize_text_field( $settings['video_group'] ?? '' );
        $ids   = sanitize_text_field( $settings['video_ids'] ?? '' );

        // Build WP_Query args
        $query_args = [
            'post_type'      => 'videos',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        if ( ! empty( $group ) ) {
            $query_args['meta_query'] = [
                [
                    'key'     => 'video_group',
                    'value'   => $group,
                    'compare' => 'LIKE',
                ],
            ];
        }

        if ( ! empty( $ids ) ) {
            $id_array = array_map( 'intval', explode( ',', $ids ) );
            $query_args['post__in'] = $id_array;
            $query_args['orderby']  = 'post__in';
        }

        $videos = new \WP_Query( $query_args );

        if ( ! $videos->have_posts() ) {
            echo '<p class="vc-no-videos">No videos found.</p>';
            return;
        }

        $total_videos     = $videos->post_count;
        $default_brand_logo = 'https://dueback.com/wp-content/uploads/2024/03/logo-Icon.png';
        $unique_id        = 'vce-' . $this->get_id();
        ?>

        <div class="vc-wrapper" id="<?php echo esc_attr( $unique_id ); ?>">
            <div class="vc-grid" id="<?php echo esc_attr( $unique_id ); ?>-grid">
                <?php
                $idx = 0;
                while ( $videos->have_posts() ) :
                    $videos->the_post();
                    $idx++;
                    $post_id  = get_the_ID();
                    $p        = get_field( 'video_platform', $post_id );
                    $platform = strtolower( trim( is_array( $p ) ? ( $p['value'] ?? $p[0] ) : $p ) );
                    $video_url = get_field( 'video_url', $post_id );
                    $video_id  = vce_extract_video_id( $platform, $video_url );

                    if ( empty( $video_id ) ) {
                        continue;
                    }

                    $is_shorts = ( strpos( $video_url, '/shorts/' ) !== false ) ? 'true' : 'false';
                    $likes     = get_field( 'likes_count', $post_id );
                    $comments  = get_field( 'comments_count', $post_id );
                    $savings   = get_field( 'savings_amount', $post_id );
                    $username  = get_field( 'username', $post_id );
                    $caption   = get_field( 'caption', $post_id );

                    $thumbnail = get_field( 'video_thumbnail', $post_id );
                    $thumb_url = is_array( $thumbnail )
                        ? ( $thumbnail['url'] ?? '' )
                        : ( is_numeric( $thumbnail )
                            ? wp_get_attachment_image_url( $thumbnail, 'large' )
                            : $thumbnail );

                    if ( ! $thumb_url && $platform === 'youtube' ) {
                        $thumb_url = "https://img.youtube.com/vi/{$video_id}/maxresdefault.jpg";
                    }

                    $avatar_logo = get_field( 'avatar_logo', $post_id );
                    $avatar_url  = is_array( $avatar_logo )
                        ? ( $avatar_logo['url'] ?? $default_brand_logo )
                        : ( is_numeric( $avatar_logo )
                            ? wp_get_attachment_image_url( $avatar_logo, 'thumbnail' )
                            : ( $avatar_logo ?: $default_brand_logo ) );
                    ?>

                    <div class="vc-card" data-index="<?php echo $idx; ?>">
                        <div class="vc-video">
                            <span class="vc-badge vc-badge--<?php echo esc_attr( $platform ); ?>">
                                <?php echo vce_get_platform_logo( $platform ); ?>
                                <span class="vc-badge-text"><?php echo ucfirst( $platform ); ?></span>
                            </span>
                            <div class="vc-thumbnail">
                                <?php if ( $thumb_url ) : ?>
                                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="Video">
                                <?php endif; ?>
                            </div>
                            <button class="vc-play"
                                    data-platform="<?php echo esc_attr( $platform ); ?>"
                                    data-video-id="<?php echo esc_attr( $video_id ); ?>"
                                    data-is-shorts="<?php echo $is_shorts; ?>">
                                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            </button>
                        </div>

                        <div class="vc-info">
                            <?php if ( $username ) : ?>
                                <div class="vc-user">
                                    <span class="vc-avatar"><img src="<?php echo esc_url( $avatar_url ); ?>"></span>
                                    <span class="vc-username"><?php echo esc_html( $username ); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ( $caption ) : ?>
                                <p class="vc-caption"><?php echo esc_html( $caption ); ?></p>
                            <?php endif; ?>

                            <?php if ( $likes || $comments || $savings ) : ?>
                                <div class="vc-footer-meta">
                                    <div class="vc-stats">
                                        <?php if ( $likes ) : ?>
                                            <span>❤️ <?php echo esc_html( $likes ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( $comments ) : ?>
                                            <span>💬 <?php echo esc_html( $comments ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ( $savings ) : ?>
                                        <span class="vc-savings">💰 $<?php echo esc_html( $savings ); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>

            <div class="vc-nav" id="<?php echo esc_attr( $unique_id ); ?>-nav">
                <?php for ( $i = 1; $i <= $total_videos; $i++ ) : ?>
                    <button class="vc-nav-dot <?php echo $i === 1 ? 'active' : ''; ?>" data-slide="<?php echo $i; ?>"></button>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Modal (one per widget instance, scoped by unique ID) -->
        <div id="<?php echo esc_attr( $unique_id ); ?>-modal" class="vc-modal">
            <div class="vc-modal-content">
                <span class="vc-modal-close">&times;</span>
                <div class="vc-modal-video-container"></div>
            </div>
        </div>

        <script>
        (function() {
            const wrapperId = '<?php echo esc_js( $unique_id ); ?>';
            const wrapper = document.getElementById(wrapperId);
            if (!wrapper) return;

            const grid     = document.getElementById(wrapperId + '-grid');
            const dots     = document.querySelectorAll('#' + wrapperId + '-nav .vc-nav-dot');
            const modal    = document.getElementById(wrapperId + '-modal');
            const modalBody = modal.querySelector('.vc-modal-video-container');
            const closeBtn  = modal.querySelector('.vc-modal-close');

            let isModalOpen = false;
            let initialScrollPos = 0;

            // Play buttons
            wrapper.querySelectorAll('.vc-play').forEach(btn => {
                btn.addEventListener('click', function() {
                    const platform = this.getAttribute('data-platform');
                    const videoId  = this.getAttribute('data-video-id');
                    const isShorts = this.getAttribute('data-is-shorts') === 'true';

                    let embedUrl   = '';
                    let ratioClass = 'vc-ratio-16-9';
                    modal.className = 'vc-modal';

                    if (platform === 'youtube') {
                        embedUrl = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&rel=0';
                        if (isShorts) {
                            ratioClass = 'vc-ratio-9-16';
                            modal.classList.add('is-portrait');
                        }
                    } else if (platform === 'tiktok') {
                        embedUrl = 'https://www.tiktok.com/player/v1/' + videoId + '?autoplay=1&rel=0';
                        ratioClass = 'vc-ratio-9-16';
                        modal.classList.add('is-portrait');
                    } else if (platform === 'instagram') {
                        embedUrl = 'https://www.instagram.com/p/' + videoId + '/embed/?autoplay=1&rel=0';
                        ratioClass = 'vc-ratio-1-1';
                        modal.classList.add('is-square');
                    }

                    if (embedUrl) {
                        modalBody.className = 'vc-modal-video-container ' + ratioClass;
                        modalBody.innerHTML = '<iframe src="' + embedUrl + '" scrolling="no" allow="autoplay; encrypted-media" allowfullscreen></iframe>';
                        modal.style.display = 'flex';
                        initialScrollPos = window.pageYOffset || document.documentElement.scrollTop;
                        isModalOpen = true;
                    }
                });
            });

            const closeModal = () => {
                modal.style.display = 'none';
                modalBody.innerHTML = '';
                isModalOpen = false;
            };

            if (closeBtn) closeBtn.onclick = closeModal;
            modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

            window.addEventListener('scroll', () => {
                if (!isModalOpen) return;
                let currentScrollPos = window.pageYOffset || document.documentElement.scrollTop;
                if (Math.abs(currentScrollPos - initialScrollPos) > 350) closeModal();
            }, { passive: true });

            // Dot navigation
            if (grid && dots.length) {
                dots.forEach(dot => {
                    dot.addEventListener('click', function() {
                        const slideIndex = this.getAttribute('data-slide');
                        const target = grid.querySelector('.vc-card[data-index="' + slideIndex + '"]');
                        if (target) {
                            const scrollPos = target.offsetLeft - (grid.offsetWidth - target.offsetWidth) / 2;
                            grid.scrollTo({ left: scrollPos, behavior: 'smooth' });
                        }
                    });
                });

                grid.addEventListener('scroll', () => {
                    let activeIndex = 1;
                    let minOffset = Infinity;
                    grid.querySelectorAll('.vc-card').forEach(card => {
                        const offset = Math.abs((card.offsetLeft + card.offsetWidth / 2) - (grid.scrollLeft + grid.offsetWidth / 2));
                        if (offset < minOffset) {
                            minOffset = offset;
                            activeIndex = card.getAttribute('data-index');
                        }
                    });
                    dots.forEach(dot => dot.classList.toggle('active', dot.getAttribute('data-slide') == activeIndex));
                }, { passive: true });
            }
        })();
        </script>

        <?php
    }
}
