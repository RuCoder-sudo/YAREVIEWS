<?php
if (!defined('ABSPATH')) {
    exit;
}

$db = new \YAReviews\Database();
$settings = get_option('yareviews_widget_badge', []);
$stats = $db->get_stats();

$theme = $atts['theme'] ?? $settings['theme'] ?? 'light';
$position = $atts['position'] ?? $settings['position'] ?? 'bottom-right';
$text = $atts['text'] ?? $settings['text'] ?? 'Наши отзывы';
$accent_color = $settings['accent_color'] ?? '#FFD700';
$show_photos = $settings['show_photos'] ?? true;

$reviews = $db->get_reviews([
    'limit' => 10,
    'min_rating' => 4,
    'status' => 'published'
]);

$badge_id = 'yareviews-badge-' . uniqid();
?>

<div class="yareviews-badge-widget theme-<?php echo esc_attr($theme); ?> position-<?php echo esc_attr($position); ?>" 
     id="<?php echo esc_attr($badge_id); ?>"
     style="--accent-color: <?php echo esc_attr($accent_color); ?>">
    
    <button class="badge-trigger" type="button">
        <span class="badge-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
        </span>
        <span class="badge-rating"><?php echo esc_html($stats['avg_rating']); ?></span>
        <span class="badge-text"><?php echo esc_html($text); ?></span>
    </button>
    
    <div class="badge-panel">
        <div class="badge-panel-header">
            <div class="badge-panel-title">
                <h3><?php echo esc_html($text); ?></h3>
                <div class="badge-panel-rating">
                    <div class="rating-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="star <?php echo $i <= round($stats['avg_rating']) ? 'filled' : ''; ?>" 
                                 width="20" height="20" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-value"><?php echo esc_html($stats['avg_rating']); ?>/5</span>
                    <span class="rating-count">(<?php echo esc_html($stats['total_reviews']); ?> отзывов)</span>
                </div>
            </div>
            <button class="badge-panel-close" type="button">×</button>
        </div>
        
        <div class="badge-panel-content">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="badge-review-item">
                        <?php if ($review->author_avatar): ?>
                            <div class="review-avatar">
                                <img src="<?php echo esc_url($review->author_avatar); ?>" 
                                     alt="<?php echo esc_attr($review->author_name); ?>"
                                     loading="lazy">
                            </div>
                        <?php else: ?>
                            <div class="review-avatar">
                                <div class="avatar-placeholder">
                                    <?php echo esc_html(mb_substr($review->author_name, 0, 1)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="review-content">
                            <div class="review-header">
                                <strong><?php echo esc_html($review->author_name); ?></strong>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <svg class="star <?php echo $i <= $review->rating ? 'filled' : ''; ?>" 
                                             width="16" height="16" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="review-text"><?php echo esc_html(wp_trim_words($review->review_text, 20)); ?></p>
                            
                            <?php if ($show_photos && !empty($review->review_photos)): 
                                $photos = json_decode($review->review_photos, true);
                                if (is_array($photos) && !empty($photos)): ?>
                                    <div class="review-photos">
                                        <?php foreach ($photos as $photo): ?>
                                            <img src="<?php echo esc_url($photo); ?>" 
                                                 alt="<?php echo esc_attr__('Фото к отзыву', 'yareviews'); ?>"
                                                 loading="lazy">
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <span class="review-date"><?php echo esc_html(mysql2date('d.m.Y', $review->review_date)); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-reviews"><?php echo esc_html__('Отзывов пока нет', 'yareviews'); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const badge = document.getElementById('<?php echo esc_js($badge_id); ?>');
    const trigger = badge.querySelector('.badge-trigger');
    const panel = badge.querySelector('.badge-panel');
    const closeBtn = badge.querySelector('.badge-panel-close');
    
    trigger.addEventListener('click', function() {
        badge.classList.toggle('active');
    });
    
    closeBtn.addEventListener('click', function() {
        badge.classList.remove('active');
    });
    
    document.addEventListener('click', function(e) {
        if (!badge.contains(e.target)) {
            badge.classList.remove('active');
        }
    });
});
</script>
