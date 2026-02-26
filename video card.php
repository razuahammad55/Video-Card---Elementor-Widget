<?php
/**
 * ============================================================
 * StudentVersion.com — Social Media Video Cards Shortcode
 * ============================================================
 * Add via: WPCode Pro → Add Snippet → PHP Snippet
 * Location: Everywhere
 * Shortcode: [sv_video_cards count="4" group="Homepage"]
 * 
 * Adapted from DueBack.com / ThriftFinder.com video card system
 * with StudentVersion "Universal College" color palette.
 * ============================================================
 */

// ─── Register Custom Post Type ───────────────────────────────
function sv_register_video_cards_cpt() {
    register_post_type('sv_video_card', array(
        'labels' => array(
            'name'               => 'Video Cards',
            'singular_name'      => 'Video Card',
            'add_new'            => 'Add New Video',
            'add_new_item'       => 'Add New Video Card',
            'edit_item'          => 'Edit Video Card',
            'all_items'          => 'All Video Cards',
            'search_items'       => 'Search Video Cards',
            'not_found'          => 'No video cards found',
            'menu_name'          => '🎬 Video Cards',
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'supports'     => array('title'),
        'menu_icon'    => 'dashicons-video-alt3',
        'menu_position'=> 25,
    ));
}
add_action('init', 'sv_register_video_cards_cpt');

// ─── Register Custom Taxonomy (Groups) ──────────────────────
function sv_register_video_groups_tax() {
    register_taxonomy('sv_video_group', 'sv_video_card', array(
        'labels' => array(
            'name'          => 'Video Groups',
            'singular_name' => 'Video Group',
            'add_new_item'  => 'Add New Group',
            'edit_item'     => 'Edit Group',
            'all_items'     => 'All Groups',
            'search_items'  => 'Search Groups',
        ),
        'public'       => false,
        'show_ui'      => true,
        'hierarchical' => true,
        'show_admin_column' => true,
    ));
}
add_action('init', 'sv_register_video_groups_tax');

// ─── Meta Box for Video Card Fields ─────────────────────────
function sv_video_card_meta_boxes() {
    add_meta_box(
        'sv_video_card_details',
        '📱 Video Card Details',
        'sv_video_card_meta_html',
        'sv_video_card',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sv_video_card_meta_boxes');

function sv_video_card_meta_html($post) {
    wp_nonce_field('sv_video_card_nonce_action', 'sv_video_card_nonce');
    
    $platform      = get_post_meta($post->ID, '_sv_platform', true) ?: 'tiktok';
    $video_url     = get_post_meta($post->ID, '_sv_video_url', true);
    $thumbnail_url = get_post_meta($post->ID, '_sv_thumbnail_url', true);
    $handle        = get_post_meta($post->ID, '_sv_handle', true);
    $caption       = get_post_meta($post->ID, '_sv_caption', true);
    $likes         = get_post_meta($post->ID, '_sv_likes', true);
    $comments      = get_post_meta($post->ID, '_sv_comments', true);
    $savings       = get_post_meta($post->ID, '_sv_savings', true);
    $avatar_url    = get_post_meta($post->ID, '_sv_avatar_url', true);
    
    echo '<table class="form-table"><tbody>';
    
    // Platform
    echo '<tr><th><label>Platform</label></th><td>';
    echo '<select name="sv_platform" style="width:200px">';
    foreach (array('tiktok' => 'TikTok', 'instagram' => 'Instagram Reels', 'youtube' => 'YouTube Shorts') as $val => $label) {
        $sel = ($platform === $val) ? ' selected' : '';
        echo "<option value=\"{$val}\"{$sel}>{$label}</option>";
    }
    echo '</select></td></tr>';
    
    // Video URL (embed URL)
    echo '<tr><th><label>Video Embed URL</label></th><td>';
    echo '<input type="url" name="sv_video_url" value="' . esc_attr($video_url) . '" class="regular-text" placeholder="https://www.tiktok.com/embed/...">';
    echo '<p class="description">The embed/iframe URL for the video</p></td></tr>';
    
    // Thumbnail
    echo '<tr><th><label>Thumbnail Image URL</label></th><td>';
    echo '<input type="url" name="sv_thumbnail_url" value="' . esc_attr($thumbnail_url) . '" class="regular-text" placeholder="https://...">';
    echo '<p class="description">Upload to Media Library and paste the URL here</p></td></tr>';
    
    // Handle
    echo '<tr><th><label>Creator Handle</label></th><td>';
    echo '<input type="text" name="sv_handle" value="' . esc_attr($handle) . '" class="regular-text" placeholder="@studentdeals">';
    echo '</td></tr>';
    
    // Avatar
    echo '<tr><th><label>Avatar Image URL</label></th><td>';
    echo '<input type="url" name="sv_avatar_url" value="' . esc_attr($avatar_url) . '" class="regular-text" placeholder="https://... (optional, uses initial if blank)">';
    echo '</td></tr>';
    
    // Caption
    echo '<tr><th><label>Caption</label></th><td>';
    echo '<textarea name="sv_caption" rows="3" class="large-text">' . esc_textarea($caption) . '</textarea>';
    echo '</td></tr>';
    
    // Likes
    echo '<tr><th><label>Likes Count</label></th><td>';
    echo '<input type="text" name="sv_likes" value="' . esc_attr($likes) . '" placeholder="24.1K">';
    echo '</td></tr>';
    
    // Comments
    echo '<tr><th><label>Comments Count</label></th><td>';
    echo '<input type="text" name="sv_comments" value="' . esc_attr($comments) . '" placeholder="412">';
    echo '</td></tr>';
    
    // Savings
    echo '<tr><th><label>Savings Badge Text</label></th><td>';
    echo '<input type="text" name="sv_savings" value="' . esc_attr($savings) . '" placeholder="$72 Saved">';
    echo '</td></tr>';
    
    echo '</tbody></table>';
}

// ─── Save Meta ──────────────────────────────────────────────
function sv_save_video_card_meta($post_id) {
    if (!isset($_POST['sv_video_card_nonce']) || 
        !wp_verify_nonce($_POST['sv_video_card_nonce'], 'sv_video_card_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    $fields = array(
        'sv_platform'      => '_sv_platform',
        'sv_video_url'     => '_sv_video_url',
        'sv_thumbnail_url' => '_sv_thumbnail_url',
        'sv_handle'        => '_sv_handle',
        'sv_caption'       => '_sv_caption',
        'sv_likes'         => '_sv_likes',
        'sv_comments'      => '_sv_comments',
        'sv_savings'       => '_sv_savings',
        'sv_avatar_url'    => '_sv_avatar_url',
    );
    
    foreach ($fields as $post_key => $meta_key) {
        if (isset($_POST[$post_key])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$post_key]));
        }
    }
}
add_action('save_post_sv_video_card', 'sv_save_video_card_meta');

// ─── Platform SVG Icons ─────────────────────────────────────
function sv_get_platform_icon($platform) {
    switch ($platform) {
        case 'tiktok':
            return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1 0-5.78 2.92 2.92 0 0 1 .88.13V9.02a6.37 6.37 0 0 0-.88-.07 6.27 6.27 0 0 0 0 12.54 6.27 6.27 0 0 0 6.27-6.27V8.56a8.28 8.28 0 0 0 3.83.95V6.07a4.73 4.73 0 0 1-.88.01v.61h.88z"/></svg>';
        case 'instagram':
            return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>';
        case 'youtube':
            return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>';
        default:
            return '';
    }
}

function sv_get_platform_label($platform) {
    switch ($platform) {
        case 'tiktok':    return 'TikTok';
        case 'instagram': return 'Reels';
        case 'youtube':   return 'Shorts';
        default:          return $platform;
    }
}

// ─── Shortcode: [sv_video_cards] ────────────────────────────
function sv_video_cards_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 4,
        'group' => '',
        'ids'   => '',
    ), $atts, 'sv_video_cards');
    
    $args = array(
        'post_type'      => 'sv_video_card',
        'posts_per_page' => intval($atts['count']),
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    
    if (!empty($atts['ids'])) {
        $args['post__in'] = array_map('intval', explode(',', $atts['ids']));
        $args['orderby']  = 'post__in';
    }
    
    if (!empty($atts['group'])) {
        $args['tax_query'] = array(array(
            'taxonomy' => 'sv_video_group',
            'field'    => 'slug',
            'terms'    => sanitize_title($atts['group']),
        ));
    }
    
    $query = new WP_Query($args);
    
    if (!$query->have_posts()) {
        return '<p style="color:#6B7280;text-align:center;">No video cards found.</p>';
    }
    
    ob_start();
    echo '<div class="sv-video-cards-wrapper">';
    
    while ($query->have_posts()) {
        $query->the_post();
        $id            = get_the_ID();
        $platform      = get_post_meta($id, '_sv_platform', true) ?: 'tiktok';
        $video_url     = get_post_meta($id, '_sv_video_url', true);
        $thumbnail_url = get_post_meta($id, '_sv_thumbnail_url', true);
        $handle        = get_post_meta($id, '_sv_handle', true);
        $caption       = get_post_meta($id, '_sv_caption', true);
        $likes         = get_post_meta($id, '_sv_likes', true);
        $comments_ct   = get_post_meta($id, '_sv_comments', true);
        $savings       = get_post_meta($id, '_sv_savings', true);
        $avatar_url    = get_post_meta($id, '_sv_avatar_url', true);
        
        $initial = strtoupper(substr(ltrim($handle, '@'), 0, 1));
        $platform_icon = sv_get_platform_icon($platform);
        $platform_label = sv_get_platform_label($platform);
        
        ?>
        <div class="sv-video-card" data-video-url="<?php echo esc_url($video_url); ?>" data-platform="<?php echo esc_attr($platform); ?>">
            <div class="sv-video-thumb">
                <?php if ($thumbnail_url): ?>
                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($handle); ?>" loading="lazy">
                <?php endif; ?>
                
                <span class="sv-platform-badge <?php echo esc_attr($platform); ?>">
                    <?php echo $platform_icon; ?>
                    <?php echo esc_html($platform_label); ?>
                </span>
                
                <button class="sv-play-btn" aria-label="Play video">
                    <svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>
                
                <?php if ($savings): ?>
                    <span class="sv-savings-badge">🎓 <?php echo esc_html($savings); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="sv-video-info">
                <div class="sv-video-user">
                    <div class="sv-video-avatar">
                        <?php if ($avatar_url): ?>
                            <img src="<?php echo esc_url($avatar_url); ?>" alt="">
                        <?php else: ?>
                            <?php echo esc_html($initial); ?>
                        <?php endif; ?>
                    </div>
                    <span class="sv-video-handle"><?php echo esc_html($handle); ?></span>
                </div>
                <?php if ($caption): ?>
                    <p class="sv-video-caption"><?php echo esc_html($caption); ?></p>
                <?php endif; ?>
                <div class="sv-video-stats">
                    <?php if ($likes): ?>
                        <span>❤️ <?php echo esc_html($likes); ?></span>
                    <?php endif; ?>
                    <?php if ($comments_ct): ?>
                        <span>💬 <?php echo esc_html($comments_ct); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    echo '</div>';
    
    // Modal container
    echo '<div class="sv-video-modal-overlay" id="svVideoModal">';
    echo '  <div class="sv-video-modal">';
    echo '    <button class="sv-video-modal-close" aria-label="Close">&times;</button>';
    echo '    <div id="svVideoModalContent"></div>';
    echo '  </div>';
    echo '</div>';
    
    // JavaScript for modal
    ?>
    <script>
    (function(){
        const cards = document.querySelectorAll('.sv-video-card');
        const modal = document.getElementById('svVideoModal');
        const modalContent = document.getElementById('svVideoModalContent');
        const closeBtn = modal ? modal.querySelector('.sv-video-modal-close') : null;
        
        if (!modal) return;
        
        cards.forEach(card => {
            card.addEventListener('click', function() {
                const url = this.dataset.videoUrl;
                if (!url) return;
                modalContent.innerHTML = '<iframe src="' + url + '" allowfullscreen allow="autoplay; encrypted-media" style="width:100%;aspect-ratio:9/16;border:none;"></iframe>';
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });
        
        function closeModal() {
            modal.classList.remove('active');
            modalContent.innerHTML = '';
            document.body.style.overflow = '';
        }
        
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    })();
    </script>
    <?php
    
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('sv_video_cards', 'sv_video_cards_shortcode');
