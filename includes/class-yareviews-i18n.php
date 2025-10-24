<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class I18n {
    
    public function load_plugin_textdomain() {
        load_plugin_textdomain(
            'yareviews',
            false,
            dirname(YAREVIEWS_PLUGIN_BASENAME) . '/languages/'
        );
    }
}
