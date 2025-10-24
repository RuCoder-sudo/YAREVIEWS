<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class Activator {
    
    public static function activate() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $reviews_table = $wpdb->prefix . 'yareviews_reviews';
        $complaints_table = $wpdb->prefix . 'yareviews_complaints';
        $settings_table = $wpdb->prefix . 'yareviews_settings';
        
        $sql_reviews = "CREATE TABLE IF NOT EXISTS $reviews_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            author_name varchar(255) NOT NULL,
            author_avatar varchar(500) DEFAULT NULL,
            rating tinyint(1) NOT NULL,
            review_text text NOT NULL,
            review_photos text DEFAULT NULL,
            review_date datetime NOT NULL,
            source varchar(50) DEFAULT 'manual',
            yandex_id varchar(100) DEFAULT NULL,
            status varchar(20) DEFAULT 'published',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY rating (rating),
            KEY status (status),
            KEY source (source),
            KEY review_date (review_date)
        ) $charset_collate;";
        
        $sql_complaints = "CREATE TABLE IF NOT EXISTS $complaints_table (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            author_name varchar(255) DEFAULT NULL,
            author_email varchar(255) DEFAULT NULL,
            author_phone varchar(50) DEFAULT NULL,
            rating tinyint(1) NOT NULL,
            complaint_text text NOT NULL,
            ip_address varchar(50) DEFAULT NULL,
            user_agent text DEFAULT NULL,
            status varchar(20) DEFAULT 'new',
            notified tinyint(1) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            resolved_at datetime DEFAULT NULL,
            PRIMARY KEY (id),
            KEY status (status),
            KEY rating (rating),
            KEY created_at (created_at)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_reviews);
        dbDelta($sql_complaints);
        
        if (!get_option('yareviews_version')) {
            add_option('yareviews_version', YAREVIEWS_VERSION);
        }
        
        if (!get_option('yareviews_settings')) {
            $default_settings = [
                'yandex_org_id' => '',
                'yandex_api_key' => '',
                'telegram_bot_token' => '',
                'telegram_chat_id' => '',
                'qr_redirect_url' => '',
                'enable_telegram_notifications' => false,
                'enable_positive_notifications' => false,
                'privacy_policy_url' => '',
                'public_offer_url' => '',
                'thank_you_page_url' => '',
                'form_name_required' => false,
                'form_email_required' => false,
                'form_phone_required' => false,
                'form_background' => 'gradient',
                'form_bg_color1' => '#667eea',
                'form_bg_color2' => '#764ba2'
            ];
            add_option('yareviews_settings', $default_settings);
        }
        
        add_rewrite_rule('^yareviews-rate/?$', 'index.php?yareviews_rate=1', 'top');
        add_rewrite_tag('%yareviews_rate%', '([^&]+)');
        
        flush_rewrite_rules();
    }
}
