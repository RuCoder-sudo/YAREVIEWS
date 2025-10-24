<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class Main {
    
    protected $loader;
    protected $plugin_name;
    protected $version;
    
    public function __construct() {
        $this->version = YAREVIEWS_VERSION;
        $this->plugin_name = 'yareviews';
        
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }
    
    private function load_dependencies() {
        require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-loader.php';
        require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-i18n.php';
        require_once YAREVIEWS_PLUGIN_DIR . 'admin/class-yareviews-admin.php';
        require_once YAREVIEWS_PLUGIN_DIR . 'public/class-yareviews-public.php';
        require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-database.php';
        require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-qr-generator.php';
        require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-telegram.php';
        require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-blocks.php';
        
        $this->loader = new Loader();
        
        $blocks = new Blocks();
    }
    
    private function set_locale() {
        $plugin_i18n = new I18n();
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }
    
    private function define_admin_hooks() {
        $plugin_admin = new \YAReviews\Admin\Admin($this->plugin_name, $this->version);
        
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
    }
    
    private function define_public_hooks() {
        $plugin_public = new \YAReviews\PublicArea\PublicArea($this->plugin_name, $this->version);
        
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
        $this->loader->add_action('rest_api_init', $plugin_public, 'register_rest_routes');
    }
    
    public function run() {
        $this->loader->run();
    }
    
    public function get_plugin_name() {
        return $this->plugin_name;
    }
    
    public function get_loader() {
        return $this->loader;
    }
    
    public function get_version() {
        return $this->version;
    }
}
