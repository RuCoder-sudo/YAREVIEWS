<?php
namespace YAReviews\Admin;

if (!defined('ABSPATH')) {
    exit;
}

class Admin {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_styles() {
        if (!$this->is_yareviews_page()) {
            return;
        }
        
        wp_enqueue_style(
            $this->plugin_name . '-admin',
            YAREVIEWS_PLUGIN_URL . 'admin/css/yareviews-admin.css',
            [],
            $this->version,
            'all'
        );
        
        wp_enqueue_style('wp-color-picker');
    }
    
    public function enqueue_scripts() {
        if (!$this->is_yareviews_page()) {
            return;
        }
        
        wp_enqueue_media();
        
        wp_enqueue_script(
            $this->plugin_name . '-admin',
            YAREVIEWS_PLUGIN_URL . 'admin/js/yareviews-admin.js',
            ['jquery', 'wp-color-picker'],
            $this->version,
            true
        );
        
        wp_localize_script($this->plugin_name . '-admin', 'yareviews_admin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('yareviews_admin_nonce'),
            'strings' => [
                'confirm_delete' => __('Вы уверены, что хотите удалить этот элемент?', 'yareviews'),
                'saved' => __('Сохранено!', 'yareviews'),
                'error' => __('Ошибка при сохранении', 'yareviews'),
                'select_image' => __('Выбрать изображение', 'yareviews'),
                'use_image' => __('Использовать это изображение', 'yareviews')
            ]
        ]);
    }
    
    private function is_yareviews_page() {
        $screen = get_current_screen();
        return $screen && strpos($screen->id, 'yareviews') !== false;
    }
    
    public function add_plugin_admin_menu() {
        add_menu_page(
            __('YAREVIEWS', 'yareviews'),
            __('YAREVIEWS', 'yareviews'),
            'manage_options',
            'yareviews',
            [$this, 'display_dashboard_page'],
            'dashicons-star-filled',
            30
        );
        
        add_submenu_page(
            'yareviews',
            __('Главная', 'yareviews'),
            __('📊 Главная', 'yareviews'),
            'manage_options',
            'yareviews',
            [$this, 'display_dashboard_page']
        );
        
        add_submenu_page(
            'yareviews',
            __('Настройки', 'yareviews'),
            __('🛠 Настройки', 'yareviews'),
            'manage_options',
            'yareviews-settings',
            [$this, 'display_settings_page']
        );
        
        add_submenu_page(
            'yareviews',
            __('Виджеты', 'yareviews'),
            __('🎨 Виджеты', 'yareviews'),
            'manage_options',
            'yareviews-widgets',
            [$this, 'display_widgets_page']
        );
        
        add_submenu_page(
            'yareviews',
            __('Все отзывы', 'yareviews'),
            __('📝 Все отзывы', 'yareviews'),
            'manage_options',
            'yareviews-reviews',
            [$this, 'display_reviews_page']
        );
        
        add_submenu_page(
            'yareviews',
            __('Претензии', 'yareviews'),
            __('⚠️ Претензии', 'yareviews'),
            'manage_options',
            'yareviews-complaints',
            [$this, 'display_complaints_page']
        );
        
        add_submenu_page(
            'yareviews',
            __('Помощь', 'yareviews'),
            __('❓ Помощь', 'yareviews'),
            'manage_options',
            'yareviews-help',
            [$this, 'display_help_page']
        );
    }
    
    public function register_settings() {
        register_setting('yareviews_settings_group', 'yareviews_settings');
        register_setting('yareviews_widgets_group', 'yareviews_widget_slider');
        register_setting('yareviews_widgets_group', 'yareviews_widget_badge');
        register_setting('yareviews_widgets_group', 'yareviews_widget_grid');
    }
    
    public function display_dashboard_page() {
        require_once YAREVIEWS_PLUGIN_DIR . 'admin/partials/dashboard.php';
    }
    
    public function display_settings_page() {
        require_once YAREVIEWS_PLUGIN_DIR . 'admin/partials/settings.php';
    }
    
    public function display_widgets_page() {
        require_once YAREVIEWS_PLUGIN_DIR . 'admin/partials/widgets.php';
    }
    
    public function display_reviews_page() {
        require_once YAREVIEWS_PLUGIN_DIR . 'admin/partials/reviews.php';
    }
    
    public function display_complaints_page() {
        require_once YAREVIEWS_PLUGIN_DIR . 'admin/partials/complaints.php';
    }
    
    public function display_help_page() {
        require_once YAREVIEWS_PLUGIN_DIR . 'admin/partials/help.php';
    }
}
