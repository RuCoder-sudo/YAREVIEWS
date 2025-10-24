<?php
if (!defined('ABSPATH')) {
    exit;
}

$db = new \YAReviews\Database();
$stats = $db->get_stats();
$recent_reviews = $db->get_reviews(['limit' => 5]);
$recent_complaints = $db->get_complaints(['limit' => 5, 'status' => 'all']);
?>

<div class="wrap yareviews-admin">
    <h1 class="yareviews-title">
        <span class="dashicons dashicons-star-filled"></span>
        <?php echo esc_html__('YAREVIEWS - Главная панель', 'yareviews'); ?>
    </h1>
    
    <div class="yareviews-dashboard">
        <div class="yareviews-stats-grid">
            <div class="yareviews-stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['total_reviews']); ?></h3>
                    <p><?php echo esc_html__('Всего отзывов', 'yareviews'); ?></p>
                </div>
            </div>
            
            <div class="yareviews-stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['avg_rating']); ?></h3>
                    <p><?php echo esc_html__('Средний рейтинг', 'yareviews'); ?></p>
                </div>
            </div>
            
            <div class="yareviews-stat-card">
                <div class="stat-icon">⚠️</div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['total_complaints']); ?></h3>
                    <p><?php echo esc_html__('Всего претензий', 'yareviews'); ?></p>
                </div>
            </div>
            
            <div class="yareviews-stat-card <?php echo $stats['new_complaints'] > 0 ? 'stat-alert' : ''; ?>">
                <div class="stat-icon">🔔</div>
                <div class="stat-content">
                    <h3><?php echo esc_html($stats['new_complaints']); ?></h3>
                    <p><?php echo esc_html__('Новые претензии', 'yareviews'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="yareviews-dashboard-content">
            <div class="yareviews-panel">
                <h2><?php echo esc_html__('Последние отзывы', 'yareviews'); ?></h2>
                <?php if (!empty($recent_reviews)): ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php echo esc_html__('Автор', 'yareviews'); ?></th>
                                <th><?php echo esc_html__('Рейтинг', 'yareviews'); ?></th>
                                <th><?php echo esc_html__('Отзыв', 'yareviews'); ?></th>
                                <th><?php echo esc_html__('Дата', 'yareviews'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_reviews as $review): ?>
                                <tr>
                                    <td><?php echo esc_html($review->author_name); ?></td>
                                    <td>
                                        <span class="yareviews-stars">
                                            <?php echo str_repeat('⭐', $review->rating); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html(wp_trim_words($review->review_text, 10)); ?></td>
                                    <td><?php echo esc_html(mysql2date('d.m.Y H:i', $review->review_date)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="yareviews-empty"><?php echo esc_html__('Отзывов пока нет', 'yareviews'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="yareviews-panel">
                <h2><?php echo esc_html__('Последние претензии', 'yareviews'); ?></h2>
                <?php if (!empty($recent_complaints)): ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php echo esc_html__('Контакт', 'yareviews'); ?></th>
                                <th><?php echo esc_html__('Рейтинг', 'yareviews'); ?></th>
                                <th><?php echo esc_html__('Претензия', 'yareviews'); ?></th>
                                <th><?php echo esc_html__('Дата', 'yareviews'); ?></th>
                                <th><?php echo esc_html__('Статус', 'yareviews'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_complaints as $complaint): ?>
                                <tr>
                                    <td><?php echo esc_html($complaint->author_name ?: $complaint->author_email ?: 'Аноним'); ?></td>
                                    <td>
                                        <span class="yareviews-stars">
                                            <?php echo str_repeat('⭐', $complaint->rating); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html(wp_trim_words($complaint->complaint_text, 10)); ?></td>
                                    <td><?php echo esc_html(mysql2date('d.m.Y H:i', $complaint->created_at)); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo esc_attr($complaint->status); ?>">
                                            <?php echo esc_html(ucfirst($complaint->status)); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="yareviews-empty"><?php echo esc_html__('Претензий пока нет', 'yareviews'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
