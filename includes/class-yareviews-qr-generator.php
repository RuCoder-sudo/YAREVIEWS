<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class QR_Generator {
    
    public static function generate_qr_url() {
        $base_url = home_url('/yareviews-rate/');
        return $base_url;
    }
    
    public static function get_qr_code_svg($size = 300) {
        $url = self::generate_qr_url();
        $api_url = 'https://api.qrserver.com/v1/create-qr-code/';
        $qr_url = add_query_arg([
            'data' => urlencode($url),
            'size' => $size . 'x' . $size,
            'format' => 'svg'
        ], $api_url);
        
        return $qr_url;
    }
    
    public static function get_qr_code_png($size = 300) {
        $url = self::generate_qr_url();
        $api_url = 'https://api.qrserver.com/v1/create-qr-code/';
        $qr_url = add_query_arg([
            'data' => urlencode($url),
            'size' => $size . 'x' . $size,
            'format' => 'png'
        ], $api_url);
        
        return $qr_url;
    }
}
