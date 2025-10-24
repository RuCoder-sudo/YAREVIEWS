<?php
if (!defined('ABSPATH')) {
    exit;
}

$db = new \YAReviews\Database();
$settings = get_option('yareviews_widget_grid', []);

$theme = $atts['theme'] ?? $settings['theme'] ?? 'light';
$min_rating = (int) ($atts['min_rating'] ?? 4);
$columns = (int) ($atts['columns'] ?? $settings['columns'] ?? 3);
$show_avatar = $settings['show_avatar'] ?? true;
$show_rating = $settings['show_rating'] ?? true;
$show_date = $settings['show_date'] ?? true;
$show_photos = $settings['show_photos'] ?? true;
$accent_color = $settings['accent_color'] ?? '#FFD700';

$load_more_enabled = isset($atts['load_more']) && $atts['load_more'] === 'true' 
    ? true 
    : ($settings['enable_load_more'] ?? false);
$initial_count = (int) ($atts['initial_count'] ?? $settings['initial_count'] ?? 6);
$load_more_count = (int) ($atts['load_more_count'] ?? $settings['load_more_count'] ?? 6);

$count = $load_more_enabled ? $initial_count : (int) ($atts['count'] ?? 6);

$reviews = $db->get_reviews([
    'limit' => $count,
    'min_rating' => $min_rating,
    'status' => 'published'
]);

if (empty($reviews)) {
    return '';
}

$widget_id = 'yareviews-grid-' . uniqid();
?>

<div class="yareviews-grid-widget theme-<?php echo esc_attr($theme); ?>" 
     id="<?php echo esc_attr($widget_id); ?>"
     data-min-rating="<?php echo esc_attr($min_rating); ?>"
     data-load-more="<?php echo $load_more_enabled ? '1' : '0'; ?>"
     data-load-more-count="<?php echo esc_attr($load_more_count); ?>"
     data-loaded="<?php echo esc_attr($count); ?>"
     data-columns="<?php echo esc_attr($columns); ?>"
     style="--accent-color: <?php echo esc_attr($accent_color); ?>; --columns: <?php echo esc_attr($columns); ?>;">
    
    <div class="yareviews-grid-container">
    <?php foreach ($reviews as $review): ?>
        <div class="yareviews-grid-item">
            <div class="grid-item-content">
                <?php if ($show_avatar && $review->author_avatar): ?>
                    <div class="review-avatar">
                        <img src="<?php echo esc_url($review->author_avatar); ?>" 
                             alt="<?php echo esc_attr($review->author_name); ?>"
                             loading="lazy">
                    </div>
                <?php elseif ($show_avatar): ?>
                    <div class="review-avatar">
                        <div class="avatar-placeholder">
                            <?php echo esc_html(mb_substr($review->author_name, 0, 1)); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="review-header">
                    <h4 class="review-author"><?php echo esc_html($review->author_name); ?></h4>
                    <?php if ($show_date): ?>
                        <span class="review-date">
                            <?php echo esc_html(mysql2date('d.m.Y', $review->review_date)); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <?php if ($show_rating): ?>
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="star <?php echo $i <= $review->rating ? 'filled' : ''; ?>" 
                                 width="18" height="18" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
                
                <p class="review-text"><?php echo esc_html($review->review_text); ?></p>
                
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
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    
    <?php if ($load_more_enabled): ?>
        <div class="yareviews-load-more-container">
            <button class="yareviews-load-more-btn" type="button">
                <?php echo esc_html__('Загрузить ещё', 'yareviews'); ?>
            </button>
            <div class="yareviews-loading" style="display: none;">
                <?php echo esc_html__('Загрузка...', 'yareviews'); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php if ($load_more_enabled): ?>
<script>
(function($) {
    'use strict';
    
    var widgetId = '<?php echo esc_js($widget_id); ?>';
    var $widget = $('#' + widgetId);
    var $container = $widget.find('.yareviews-grid-container');
    var $loadMoreBtn = $widget.find('.yareviews-load-more-btn');
    var $loading = $widget.find('.yareviews-loading');
    
    var minRating = parseInt($widget.attr('data-min-rating'));
    var loadMoreCount = parseInt($widget.attr('data-load-more-count'));
    var loadedCount = parseInt($widget.attr('data-loaded'));
    var isLoading = false;
    
    $loadMoreBtn.on('click', function() {
        if (isLoading) return;
        
        isLoading = true;
        $loadMoreBtn.hide();
        $loading.show();
        
        $.ajax({
            url: yareviews_public.rest_url + 'load-more',
            method: 'GET',
            data: {
                offset: loadedCount,
                limit: loadMoreCount,
                min_rating: minRating
            },
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce', yareviews_public.nonce);
            },
            success: function(response) {
                if (response && response.length > 0) {
                    response.forEach(function(review) {
                        var reviewHtml = buildReviewHtml(review);
                        $container.append(reviewHtml);
                    });
                    
                    loadedCount += response.length;
                    $widget.attr('data-loaded', loadedCount);
                    
                    if (response.length < loadMoreCount) {
                        $loadMoreBtn.remove();
                        $loading.remove();
                    } else {
                        $loadMoreBtn.show();
                        $loading.hide();
                    }
                } else {
                    $loadMoreBtn.remove();
                    $loading.html('<?php echo esc_js(__('Больше отзывов нет', 'yareviews')); ?>').show();
                }
                isLoading = false;
            },
            error: function() {
                $loading.html('<?php echo esc_js(__('Ошибка загрузки', 'yareviews')); ?>').show();
                isLoading = false;
            }
        });
    });
    
    function buildReviewHtml(review) {
        var html = '<div class="yareviews-grid-item"><div class="grid-item-content">';
        
        <?php if ($show_avatar): ?>
        if (review.author_avatar) {
            html += '<div class="review-avatar">';
            html += '<img src="' + review.author_avatar + '" alt="' + review.author_name + '" loading="lazy">';
            html += '</div>';
        } else {
            html += '<div class="review-avatar">';
            html += '<div class="avatar-placeholder">' + review.author_name.charAt(0) + '</div>';
            html += '</div>';
        }
        <?php endif; ?>
        
        html += '<div class="review-header">';
        html += '<h4 class="review-author">' + review.author_name + '</h4>';
        
        <?php if ($show_date): ?>
        var date = new Date(review.review_date);
        var formattedDate = date.toLocaleDateString('ru-RU');
        html += '<span class="review-date">' + formattedDate + '</span>';
        <?php endif; ?>
        
        html += '</div>';
        
        <?php if ($show_rating): ?>
        html += '<div class="review-rating">';
        for (var i = 1; i <= 5; i++) {
            var starClass = i <= review.rating ? 'filled' : '';
            html += '<svg class="star ' + starClass + '" width="18" height="18" viewBox="0 0 20 20">';
            html += '<path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>';
            html += '</svg>';
        }
        html += '</div>';
        <?php endif; ?>
        
        html += '<p class="review-text">' + review.review_text + '</p>';
        
        html += '</div></div>';
        return html;
    }
})(jQuery);
</script>
<?php endif; ?>
