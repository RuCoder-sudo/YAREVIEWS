<?php
namespace YAReviews\PublicArea;

if (!defined('ABSPATH')) {
    exit;
}

class PublicArea {
    
    private $plugin_name;
    private $version;
    
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        
        add_action('init', [$this, 'add_rewrite_rules']);
        add_action('template_redirect', [$this, 'rating_form_template']);
    }
    
    public function add_rewrite_rules() {
        add_rewrite_rule('^yareviews-rate/?$', 'index.php?yareviews_rate=1', 'top');
        add_rewrite_tag('%yareviews_rate%', '([^&]+)');
    }
    
    public function rating_form_template() {
        if (get_query_var('yareviews_rate')) {
            include YAREVIEWS_PLUGIN_DIR . 'public/partials/rating-form.php';
            exit;
        }
    }
    
    public function enqueue_styles() {
        wp_enqueue_style(
            $this->plugin_name,
            YAREVIEWS_PLUGIN_URL . 'public/css/yareviews-public.css',
            [],
            $this->version,
            'all'
        );
        
        wp_enqueue_style(
            $this->plugin_name . '-swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            [],
            '11.0.0',
            'all'
        );
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            $this->plugin_name . '-swiper',
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            [],
            '11.0.0',
            true
        );
        
        wp_enqueue_script(
            $this->plugin_name,
            YAREVIEWS_PLUGIN_URL . 'public/js/yareviews-public.js',
            ['jquery', $this->plugin_name . '-swiper'],
            $this->version,
            true
        );
        
        wp_localize_script($this->plugin_name, 'yareviews_public', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => rest_url('yareviews/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
            'strings' => [
                'submit' => __('Отправить', 'yareviews'),
                'error' => __('Произошла ошибка', 'yareviews'),
                'success' => __('Спасибо за ваш отзыв!', 'yareviews')
            ]
        ]);
    }
    
    public function register_shortcodes() {
        add_shortcode('yareviews', [$this, 'render_shortcode']);
        add_shortcode('yareviews_slider', [$this, 'render_slider_shortcode']);
        add_shortcode('yareviews_badge', [$this, 'render_badge_shortcode']);
        add_shortcode('yareviews_grid', [$this, 'render_grid_shortcode']);
    }
    
    public function render_shortcode($atts) {
        $atts = shortcode_atts([
            'type' => 'slider',
            'theme' => 'light',
            'count' => 5,
            'min_rating' => 0
        ], $atts);
        
        switch ($atts['type']) {
            case 'badge':
                return $this->render_badge_shortcode($atts);
            case 'grid':
                return $this->render_grid_shortcode($atts);
            case 'slider':
            default:
                return $this->render_slider_shortcode($atts);
        }
    }
    
    public function render_slider_shortcode($atts) {
        $atts = shortcode_atts([
            'theme' => 'light',
            'count' => 5,
            'min_rating' => 4,
            'autoplay' => 'true',
            'slides_per_view' => 3
        ], $atts);
        
        ob_start();
        require YAREVIEWS_PLUGIN_DIR . 'public/partials/slider-widget.php';
        return ob_get_clean();
    }
    
    public function render_badge_shortcode($atts) {
        $atts = shortcode_atts([
            'theme' => 'light',
            'position' => 'bottom-right',
            'text' => 'Наши отзывы'
        ], $atts);
        
        ob_start();
        require YAREVIEWS_PLUGIN_DIR . 'public/partials/badge-widget.php';
        return ob_get_clean();
    }
    
    public function render_grid_shortcode($atts) {
        $atts = shortcode_atts([
            'theme' => 'light',
            'count' => 6,
            'min_rating' => 4,
            'columns' => 3,
            'load_more' => 'false',
            'initial_count' => 6,
            'load_more_count' => 6
        ], $atts);
        
        ob_start();
        require YAREVIEWS_PLUGIN_DIR . 'public/partials/grid-widget.php';
        return ob_get_clean();
    }
    
    public function register_rest_routes() {
        register_rest_route('yareviews/v1', '/submit-rating', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_rating_submission'],
            'permission_callback' => '__return_true'
        ]);
        
        register_rest_route('yareviews/v1', '/reviews', [
            'methods' => 'GET',
            'callback' => [$this, 'get_reviews_api'],
            'permission_callback' => '__return_true'
        ]);
        
        register_rest_route('yareviews/v1', '/load-more', [
            'methods' => 'GET',
            'callback' => [$this, 'load_more_reviews'],
            'permission_callback' => '__return_true'
        ]);
    }
    
    public function handle_rating_submission($request) {
        $rating = (int) $request->get_param('rating');
        $text = sanitize_textarea_field($request->get_param('text'));
        $name = sanitize_text_field($request->get_param('name'));
        $email = sanitize_email($request->get_param('email'));
        $phone = sanitize_text_field($request->get_param('phone'));
        $consent_data = (bool) $request->get_param('consent_data');
        $consent_newsletter = (bool) $request->get_param('consent_newsletter');
        
        if (!$consent_data) {
            return new \WP_Error('consent_required', __('Необходимо дать согласие на обработку персональных данных', 'yareviews'), ['status' => 400]);
        }
        
        $settings = get_option('yareviews_settings', []);
        $db = new \YAReviews\Database();
        
        if ($rating >= 4) {
            if (!empty($settings['enable_positive_notifications']) || !empty($settings['enable_telegram_notifications'])) {
                $telegram = new \YAReviews\Telegram();
                $telegram->send_review_notification([
                    'rating' => $rating,
                    'review_text' => $text,
                    'author_name' => $name,
                    'author_email' => $email,
                    'author_phone' => $phone
                ]);
            }
        } else {
            $complaint_id = $db->insert_complaint([
                'author_name' => $name,
                'author_email' => $email,
                'author_phone' => $phone,
                'rating' => $rating,
                'complaint_text' => $text
            ]);
            
            if ($complaint_id && (!empty($settings['enable_telegram_notifications']) || !empty($settings['enable_positive_notifications']))) {
                $telegram = new \YAReviews\Telegram();
                $sent = $telegram->send_review_notification([
                    'rating' => $rating,
                    'review_text' => $text,
                    'author_name' => $name,
                    'author_email' => $email,
                    'author_phone' => $phone
                ]);
                
                if ($sent) {
                    $db->update_complaint($complaint_id, ['notified' => 1]);
                }
            }
        }
        
        return [
            'success' => true,
            'message' => __('Спасибо за ваш отзыв!', 'yareviews')
        ];
    }
    
    public function get_reviews_api($request) {
        $db = new \YAReviews\Database();
        
        $reviews = $db->get_reviews([
            'limit' => (int) $request->get_param('count') ?: 10,
            'min_rating' => (int) $request->get_param('min_rating') ?: 0
        ]);
        
        return rest_ensure_response($reviews);
    }
    
    public function load_more_reviews($request) {
        $db = new \YAReviews\Database();
        
        $offset = (int) $request->get_param('offset') ?: 0;
        $limit = (int) $request->get_param('limit') ?: 6;
        $min_rating = (int) $request->get_param('min_rating') ?: 0;
        
        $reviews = $db->get_reviews([
            'limit' => $limit,
            'offset' => $offset,
            'min_rating' => $min_rating,
            'status' => 'published'
        ]);
        
        return rest_ensure_response($reviews);
    }
}
