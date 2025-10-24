<?php
if (!defined('ABSPATH')) {
    exit;
}

$db = new \YAReviews\Database();
$settings = get_option('yareviews_widget_slider', []);

$theme = $atts['theme'] ?? $settings['theme'] ?? 'light';
$count = (int) ($atts['count'] ?? 5);
$min_rating = (int) ($atts['min_rating'] ?? 4);
$slides_per_view = (int) ($atts['slides_per_view'] ?? $settings['slides_per_view'] ?? 3);
$autoplay = ($atts['autoplay'] ?? $settings['autoplay'] ?? true) === 'true' || ($atts['autoplay'] ?? $settings['autoplay'] ?? true) === true;
$show_navigation = $settings['show_navigation'] ?? true;
$show_pagination = $settings['show_pagination'] ?? true;
$show_avatar = $settings['show_avatar'] ?? true;
$show_rating = $settings['show_rating'] ?? true;
$show_date = $settings['show_date'] ?? true;
$show_photos = $settings['show_photos'] ?? true;
$accent_color = $settings['accent_color'] ?? '#FFD700';

$reviews = $db->get_reviews([
    'limit' => $count,
    'min_rating' => $min_rating,
    'status' => 'published'
]);

if (empty($reviews)) {
    return '';
}

$slider_id = 'yareviews-slider-' . uniqid();
?>

<div class="yareviews-slider-widget theme-<?php echo esc_attr($theme); ?>" 
     style="--accent-color: <?php echo esc_attr($accent_color); ?>">
    <div class="swiper" id="<?php echo esc_attr($slider_id); ?>">
        <div class="swiper-wrapper">
            <?php foreach ($reviews as $review): ?>
                <div class="swiper-slide">
                    <div class="yareviews-review-card">
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
                        
                        <div class="review-content">
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
                                             width="20" height="20" viewBox="0 0 20 20">
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
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($show_navigation): ?>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        <?php endif; ?>
        
        <?php if ($show_pagination): ?>
            <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('#<?php echo esc_js($slider_id); ?>', {
        slidesPerView: 1,
        spaceBetween: 20,
        <?php if ($autoplay): ?>
        autoplay: {
            delay: <?php echo (int) ($settings['autoplay_delay'] ?? 3000); ?>,
            disableOnInteraction: false,
        },
        <?php endif; ?>
        <?php if ($show_navigation): ?>
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        <?php endif; ?>
        <?php if ($show_pagination): ?>
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        <?php endif; ?>
        breakpoints: {
            640: {
                slidesPerView: Math.min(2, <?php echo (int) $slides_per_view; ?>),
            },
            1024: {
                slidesPerView: <?php echo (int) $slides_per_view; ?>,
            },
        },
    });
});
</script>
