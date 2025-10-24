<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class Blocks {
    
    public function __construct() {
        add_action('init', [$this, 'register_blocks']);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);
    }
    
    public function register_blocks() {
        register_block_type('yareviews/slider', [
            'render_callback' => [$this, 'render_slider_block'],
            'attributes' => [
                'count' => [
                    'type' => 'number',
                    'default' => 5
                ],
                'minRating' => [
                    'type' => 'number',
                    'default' => 4
                ],
                'theme' => [
                    'type' => 'string',
                    'default' => 'light'
                ],
                'slidesPerView' => [
                    'type' => 'number',
                    'default' => 3
                ],
                'autoplay' => [
                    'type' => 'boolean',
                    'default' => true
                ]
            ]
        ]);
        
        register_block_type('yareviews/badge', [
            'render_callback' => [$this, 'render_badge_block'],
            'attributes' => [
                'position' => [
                    'type' => 'string',
                    'default' => 'bottom-right'
                ],
                'text' => [
                    'type' => 'string',
                    'default' => 'Наши отзывы'
                ],
                'theme' => [
                    'type' => 'string',
                    'default' => 'light'
                ]
            ]
        ]);
        
        register_block_type('yareviews/grid', [
            'render_callback' => [$this, 'render_grid_block'],
            'attributes' => [
                'count' => [
                    'type' => 'number',
                    'default' => 6
                ],
                'minRating' => [
                    'type' => 'number',
                    'default' => 4
                ],
                'columns' => [
                    'type' => 'number',
                    'default' => 3
                ],
                'theme' => [
                    'type' => 'string',
                    'default' => 'light'
                ]
            ]
        ]);
    }
    
    public function render_slider_block($attributes) {
        $atts = [
            'count' => $attributes['count'] ?? 5,
            'min_rating' => $attributes['minRating'] ?? 4,
            'theme' => $attributes['theme'] ?? 'light',
            'slides_per_view' => $attributes['slidesPerView'] ?? 3,
            'autoplay' => $attributes['autoplay'] ?? true ? 'true' : 'false'
        ];
        
        ob_start();
        require YAREVIEWS_PLUGIN_DIR . 'public/partials/slider-widget.php';
        return ob_get_clean();
    }
    
    public function render_badge_block($attributes) {
        $atts = [
            'position' => $attributes['position'] ?? 'bottom-right',
            'text' => $attributes['text'] ?? 'Наши отзывы',
            'theme' => $attributes['theme'] ?? 'light'
        ];
        
        ob_start();
        require YAREVIEWS_PLUGIN_DIR . 'public/partials/badge-widget.php';
        return ob_get_clean();
    }
    
    public function render_grid_block($attributes) {
        $atts = [
            'count' => $attributes['count'] ?? 6,
            'min_rating' => $attributes['minRating'] ?? 4,
            'columns' => $attributes['columns'] ?? 3,
            'theme' => $attributes['theme'] ?? 'light'
        ];
        
        ob_start();
        require YAREVIEWS_PLUGIN_DIR . 'public/partials/grid-widget.php';
        return ob_get_clean();
    }
    
    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'yareviews-blocks-editor',
            YAREVIEWS_PLUGIN_URL . 'blocks/blocks.js',
            ['wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n'],
            YAREVIEWS_VERSION,
            true
        );
        
        wp_enqueue_style(
            'yareviews-blocks-editor',
            YAREVIEWS_PLUGIN_URL . 'blocks/editor.css',
            ['wp-edit-blocks'],
            YAREVIEWS_VERSION
        );
    }
}
