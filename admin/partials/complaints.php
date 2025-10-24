<?php
if (!defined('ABSPATH')) {
    exit;
}

$db = new \YAReviews\Database();

if (isset($_GET['action']) && $_GET['action'] === 'resolve' && isset($_GET['id'])) {
    check_admin_referer('yareviews_resolve_complaint_' . $_GET['id']);
    $db->update_complaint((int) $_GET['id'], [
        'status' => 'resolved',
        'resolved_at' => current_time('mysql')
    ]);
    echo '<div class="notice notice-success"><p>' . esc_html__('Претензия отмечена как решённая!', 'yareviews') . '</p></div>';
}

$status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'new';
$complaints = $db->get_complaints(['status' => $status, 'limit' => 50]);
?>

<div class="wrap yareviews-admin">
    <h1 class="yareviews-title">
        <span class="dashicons dashicons-warning"></span>
        <?php echo esc_html__('Претензии клиентов', 'yareviews'); ?>
    </h1>
    
    <div class="yareviews-filter-tabs">
        <a href="<?php echo esc_url(admin_url('admin.php?page=yareviews-complaints&status=new')); ?>" 
           class="filter-tab <?php echo $status === 'new' ? 'active' : ''; ?>">
            <?php echo esc_html__('Новые', 'yareviews'); ?>
        </a>
        <a href="<?php echo esc_url(admin_url('admin.php?page=yareviews-complaints&status=resolved')); ?>" 
           class="filter-tab <?php echo $status === 'resolved' ? 'active' : ''; ?>">
            <?php echo esc_html__('Решённые', 'yareviews'); ?>
        </a>
        <a href="<?php echo esc_url(admin_url('admin.php?page=yareviews-complaints&status=all')); ?>" 
           class="filter-tab <?php echo $status === 'all' ? 'active' : ''; ?>">
            <?php echo esc_html__('Все', 'yareviews'); ?>
        </a>
    </div>
    
    <div class="yareviews-complaints-list">
        <?php if (!empty($complaints)): ?>
            <?php foreach ($complaints as $complaint): ?>
                <div class="yareviews-complaint-card">
                    <div class="complaint-header">
                        <div class="complaint-rating">
                            <span class="yareviews-stars">
                                <?php echo str_repeat('⭐', $complaint->rating); ?>
                            </span>
                            <span class="rating-text"><?php echo esc_html($complaint->rating); ?>/5</span>
                        </div>
                        <div class="complaint-date">
                            <?php echo esc_html(mysql2date('d.m.Y H:i', $complaint->created_at)); ?>
                        </div>
                    </div>
                    
                    <div class="complaint-body">
                        <p class="complaint-text"><?php echo esc_html($complaint->complaint_text); ?></p>
                    </div>
                    
                    <div class="complaint-footer">
                        <div class="complaint-contact">
                            <?php if ($complaint->author_name): ?>
                                <div><strong><?php echo esc_html__('Имя:', 'yareviews'); ?></strong> <?php echo esc_html($complaint->author_name); ?></div>
                            <?php endif; ?>
                            <?php if ($complaint->author_email): ?>
                                <div><strong><?php echo esc_html__('Email:', 'yareviews'); ?></strong> 
                                    <a href="mailto:<?php echo esc_attr($complaint->author_email); ?>">
                                        <?php echo esc_html($complaint->author_email); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <?php if ($complaint->author_phone): ?>
                                <div><strong><?php echo esc_html__('Телефон:', 'yareviews'); ?></strong> 
                                    <a href="tel:<?php echo esc_attr($complaint->author_phone); ?>">
                                        <?php echo esc_html($complaint->author_phone); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="complaint-actions">
                            <span class="status-badge status-<?php echo esc_attr($complaint->status); ?>">
                                <?php echo esc_html(ucfirst($complaint->status)); ?>
                            </span>
                            
                            <?php if ($complaint->status === 'new'): ?>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=yareviews-complaints&action=resolve&id=' . $complaint->id), 'yareviews_resolve_complaint_' . $complaint->id)); ?>" 
                                   class="button button-small button-primary">
                                    <?php echo esc_html__('Отметить как решено', 'yareviews'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="yareviews-empty-state">
                <p><?php echo esc_html__('Претензий нет. Отличная работа!', 'yareviews'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
