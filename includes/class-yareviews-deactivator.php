<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class Deactivator {
    
    public static function deactivate() {
        delete_option('rewrite_rules');
        flush_rewrite_rules();
    }
}
