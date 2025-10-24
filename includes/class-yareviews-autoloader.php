<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class Autoloader {
    
    public static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }
    
    public static function autoload($class) {
        $prefix = 'YAReviews\\';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        
        $relative_class = substr($class, $len);
        
        if (strpos($relative_class, 'Admin\\') === 0) {
            $class_name = str_replace('Admin\\', '', $relative_class);
            $file = YAREVIEWS_PLUGIN_DIR . 'admin/class-yareviews-' . strtolower($class_name) . '.php';
        } elseif (strpos($relative_class, 'PublicArea\\') === 0) {
            $class_name = str_replace('PublicArea\\', '', $relative_class);
            $file = YAREVIEWS_PLUGIN_DIR . 'public/class-yareviews-' . strtolower($class_name) . '.php';
        } else {
            $file = YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';
        }
        
        if (file_exists($file)) {
            require $file;
        }
    }
}
