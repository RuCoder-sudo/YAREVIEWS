<?php
if (!defined('ABSPATH')) {
    exit;
}

$db = new \YAReviews\Database();

if (isset($_POST['yareviews_add_review'])) {
    check_admin_referer('yareviews_add_review_action', 'yareviews_add_review_nonce');
    
    $review_data = [
        'author_name' => sanitize_text_field($_POST['author_name']),
        'author_avatar' => esc_url_raw($_POST['author_avatar'] ?? ''),
        'rating' => (int) $_POST['rating'],
        'review_text' => sanitize_textarea_field($_POST['review_text']),
        'review_date' => sanitize_text_field($_POST['review_date']),
        'source' => 'manual',
        'status' => 'published'
    ];
    
    $result = $db->insert_review($review_data);
    
    if ($result) {
        echo '<div class="notice notice-success"><p>' . esc_html__('Отзыв добавлен!', 'yareviews') . '</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>' . esc_html__('Ошибка при добавлении отзыва', 'yareviews') . '</p></div>';
    }
}

if (isset($_POST['yareviews_edit_review'])) {
    check_admin_referer('yareviews_edit_review_action', 'yareviews_edit_review_nonce');
    
    $review_id = (int) $_POST['review_id'];
    $review_data = [
        'author_name' => sanitize_text_field($_POST['author_name']),
        'author_avatar' => esc_url_raw($_POST['author_avatar'] ?? ''),
        'rating' => (int) $_POST['rating'],
        'review_text' => sanitize_textarea_field($_POST['review_text']),
        'review_date' => sanitize_text_field($_POST['review_date'])
    ];
    
    $result = $db->update_review($review_id, $review_data);
    
    if ($result !== false) {
        echo '<div class="notice notice-success"><p>' . esc_html__('Отзыв обновлен!', 'yareviews') . '</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>' . esc_html__('Ошибка при обновлении отзыва', 'yareviews') . '</p></div>';
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    check_admin_referer('yareviews_delete_review_' . $_GET['id']);
    $db->delete_review((int) $_GET['id']);
    echo '<div class="notice notice-success"><p>' . esc_html__('Отзыв удален!', 'yareviews') . '</p></div>';
}

$page = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$reviews = $db->get_reviews([
    'limit' => $per_page,
    'offset' => $offset,
    'status' => 'published'
]);

$edit_review_id = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$edit_review = $edit_review_id ? $db->get_review_by_id($edit_review_id) : null;
?>

<div class="wrap yareviews-admin">
    <h1 class="yareviews-title">
        <span class="dashicons dashicons-star-filled"></span>
        <?php echo esc_html__('Все отзывы', 'yareviews'); ?>
    </h1>
    
    <div class="yareviews-add-review-section">
        <?php if (!$edit_review): ?>
            <button type="button" class="button button-primary" id="yareviews-toggle-add-form">
                <?php echo esc_html__('+ Добавить отзыв вручную', 'yareviews'); ?>
            </button>
        <?php endif; ?>
        
        <div id="yareviews-add-review-form" style="display: <?php echo $edit_review ? 'block' : 'none'; ?>;" class="yareviews-form-box">
            <h3>
                <?php echo $edit_review ? esc_html__('Редактировать отзыв', 'yareviews') : esc_html__('Добавить новый отзыв', 'yareviews'); ?>
            </h3>
            <form method="post" action="">
                <?php if ($edit_review): ?>
                    <?php wp_nonce_field('yareviews_edit_review_action', 'yareviews_edit_review_nonce'); ?>
                    <input type="hidden" name="review_id" value="<?php echo esc_attr($edit_review->id); ?>">
                <?php else: ?>
                    <?php wp_nonce_field('yareviews_add_review_action', 'yareviews_add_review_nonce'); ?>
                <?php endif; ?>
                
                <table class="form-table">
                    <tr>
                        <th><label for="author_name"><?php echo esc_html__('Имя автора', 'yareviews'); ?> *</label></th>
                        <td><input type="text" id="author_name" name="author_name" class="regular-text" value="<?php echo $edit_review ? esc_attr($edit_review->author_name) : ''; ?>" required></td>
                    </tr>
                    <tr>
                        <th><label for="author_avatar"><?php echo esc_html__('Аватар', 'yareviews'); ?></label></th>
                        <td>
                            <div class="yareviews-avatar-upload">
                                <input type="url" id="author_avatar" name="author_avatar" class="regular-text" placeholder="https://..." value="<?php echo $edit_review ? esc_url($edit_review->author_avatar) : ''; ?>" readonly>
                                <button type="button" class="button yareviews-upload-avatar-btn">
                                    <?php echo esc_html__('Выбрать из библиотеки', 'yareviews'); ?>
                                </button>
                                <button type="button" class="button yareviews-remove-avatar-btn" style="display:<?php echo ($edit_review && $edit_review->author_avatar) ? 'inline-block' : 'none'; ?>;">
                                    <?php echo esc_html__('Удалить', 'yareviews'); ?>
                                </button>
                                <div class="yareviews-avatar-preview" style="display:<?php echo ($edit_review && $edit_review->author_avatar) ? 'block' : 'none'; ?>; margin-top:10px;">
                                    <img src="<?php echo $edit_review ? esc_url($edit_review->author_avatar) : ''; ?>" alt="" style="max-width:100px; height:auto; border-radius:50%;">
                                </div>
                            </div>
                            <p class="description"><?php echo esc_html__('Выберите изображение из медиабиблиотеки WordPress', 'yareviews'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="rating"><?php echo esc_html__('Рейтинг', 'yareviews'); ?> *</label></th>
                        <td>
                            <select id="rating" name="rating" required>
                                <option value="5" <?php echo ($edit_review && $edit_review->rating == 5) ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ (5)</option>
                                <option value="4" <?php echo ($edit_review && $edit_review->rating == 4) ? 'selected' : ''; ?>>⭐⭐⭐⭐ (4)</option>
                                <option value="3" <?php echo ($edit_review && $edit_review->rating == 3) ? 'selected' : ''; ?>>⭐⭐⭐ (3)</option>
                                <option value="2" <?php echo ($edit_review && $edit_review->rating == 2) ? 'selected' : ''; ?>>⭐⭐ (2)</option>
                                <option value="1" <?php echo ($edit_review && $edit_review->rating == 1) ? 'selected' : ''; ?>>⭐ (1)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="review_text"><?php echo esc_html__('Текст отзыва', 'yareviews'); ?> *</label></th>
                        <td><textarea id="review_text" name="review_text" rows="4" class="large-text" required><?php echo $edit_review ? esc_textarea($edit_review->review_text) : ''; ?></textarea></td>
                    </tr>
                    <tr>
                        <th><label for="review_date"><?php echo esc_html__('Дата отзыва', 'yareviews'); ?> *</label></th>
                        <td><input type="datetime-local" id="review_date" name="review_date" value="<?php echo $edit_review ? esc_attr(mysql2date('Y-m-d\TH:i', $edit_review->review_date)) : esc_attr(current_time('Y-m-d\TH:i')); ?>" required></td>
                    </tr>
                </table>
                
                <p class="submit">
                    <?php if ($edit_review): ?>
                        <input type="submit" name="yareviews_edit_review" class="button button-primary" value="<?php echo esc_attr__('Обновить отзыв', 'yareviews'); ?>">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=yareviews-reviews')); ?>" class="button"><?php echo esc_html__('Отмена', 'yareviews'); ?></a>
                    <?php else: ?>
                        <input type="submit" name="yareviews_add_review" class="button button-primary" value="<?php echo esc_attr__('Добавить отзыв', 'yareviews'); ?>">
                        <button type="button" class="button" id="yareviews-cancel-add"><?php echo esc_html__('Отмена', 'yareviews'); ?></button>
                    <?php endif; ?>
                </p>
            </form>
        </div>
    </div>
    
    <div class="yareviews-reviews-list">
        <?php if (!empty($reviews)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th width="50"><?php echo esc_html__('ID', 'yareviews'); ?></th>
                        <th><?php echo esc_html__('Автор', 'yareviews'); ?></th>
                        <th><?php echo esc_html__('Рейтинг', 'yareviews'); ?></th>
                        <th><?php echo esc_html__('Отзыв', 'yareviews'); ?></th>
                        <th width="150"><?php echo esc_html__('Дата', 'yareviews'); ?></th>
                        <th width="100"><?php echo esc_html__('Источник', 'yareviews'); ?></th>
                        <th width="100"><?php echo esc_html__('Действия', 'yareviews'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?php echo esc_html($review->id); ?></td>
                            <td>
                                <div class="yareviews-author-cell">
                                    <?php if ($review->author_avatar): ?>
                                        <img src="<?php echo esc_url($review->author_avatar); ?>" 
                                             alt="<?php echo esc_attr($review->author_name); ?>" 
                                             class="yareviews-avatar-small">
                                    <?php endif; ?>
                                    <strong><?php echo esc_html($review->author_name); ?></strong>
                                </div>
                            </td>
                            <td>
                                <span class="yareviews-stars">
                                    <?php echo str_repeat('⭐', $review->rating); ?>
                                </span>
                            </td>
                            <td><?php echo esc_html(wp_trim_words($review->review_text, 15)); ?></td>
                            <td><?php echo esc_html(mysql2date('d.m.Y H:i', $review->review_date)); ?></td>
                            <td>
                                <span class="source-badge source-<?php echo esc_attr($review->source); ?>">
                                    <?php echo esc_html(ucfirst($review->source)); ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=yareviews-reviews&edit=' . $review->id)); ?>" 
                                   class="button button-small">
                                    <?php echo esc_html__('Редактировать', 'yareviews'); ?>
                                </a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=yareviews-reviews&action=delete&id=' . $review->id), 'yareviews_delete_review_' . $review->id)); ?>" 
                                   class="button button-small button-link-delete" 
                                   onclick="return confirm('<?php echo esc_js(__('Удалить этот отзыв?', 'yareviews')); ?>');">
                                    <?php echo esc_html__('Удалить', 'yareviews'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="yareviews-empty-state">
                <p><?php echo esc_html__('Отзывов пока нет. Добавьте первый отзыв вручную или настройте синхронизацию с Яндекс!', 'yareviews'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
