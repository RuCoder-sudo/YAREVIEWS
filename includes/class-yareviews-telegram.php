<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class Telegram {
    
    private $bot_token;
    private $chat_id;
    
    public function __construct() {
        $settings = get_option('yareviews_settings', []);
        $this->bot_token = $settings['telegram_bot_token'] ?? '';
        $this->chat_id = $settings['telegram_chat_id'] ?? '';
    }
    
    public function send_message($message) {
        if (empty($this->bot_token) || empty($this->chat_id)) {
            return false;
        }
        
        $url = "https://api.telegram.org/bot{$this->bot_token}/sendMessage";
        
        $data = [
            'chat_id' => $this->chat_id,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];
        
        $response = wp_remote_post($url, [
            'body' => $data,
            'timeout' => 15
        ]);
        
        if (is_wp_error($response)) {
            error_log('YAREVIEWS Telegram Error: ' . $response->get_error_message());
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);
        
        return isset($result['ok']) && $result['ok'];
    }
    
    public function send_complaint_notification($complaint) {
        $rating_stars = str_repeat('⭐', $complaint['rating']);
        
        $message = "🚨 <b>Новая претензия!</b>\n\n";
        $message .= "Оценка: {$rating_stars} ({$complaint['rating']}/5)\n\n";
        $message .= "<b>Текст:</b>\n{$complaint['complaint_text']}\n\n";
        
        if (!empty($complaint['author_name'])) {
            $message .= "Имя: {$complaint['author_name']}\n";
        }
        if (!empty($complaint['author_email'])) {
            $message .= "Email: {$complaint['author_email']}\n";
        }
        if (!empty($complaint['author_phone'])) {
            $message .= "Телефон: {$complaint['author_phone']}\n";
        }
        
        $message .= "\nДата: " . current_time('d.m.Y H:i');
        
        return $this->send_message($message);
    }
    
    public function send_positive_review_notification($review) {
        $rating_stars = str_repeat('⭐', $review['rating']);
        
        $message = "✅ <b>Новый положительный отзыв!</b>\n\n";
        $message .= "Оценка: {$rating_stars} ({$review['rating']}/5)\n\n";
        $message .= "<b>Автор:</b> {$review['author_name']}\n\n";
        $message .= "<b>Текст:</b>\n" . mb_substr($review['review_text'], 0, 300);
        
        if (mb_strlen($review['review_text']) > 300) {
            $message .= "...";
        }
        
        return $this->send_message($message);
    }
    
    public function send_review_notification($review) {
        $rating_stars = str_repeat('⭐', $review['rating']);
        $icon = $review['rating'] >= 4 ? '✅' : '🚨';
        $title = $review['rating'] >= 4 ? 'Новый отзыв!' : 'Новый отзыв (низкая оценка)';
        
        $message = "{$icon} <b>{$title}</b>\n\n";
        $message .= "Оценка: {$rating_stars} ({$review['rating']}/5)\n\n";
        
        if (!empty($review['author_name'])) {
            $message .= "<b>Имя:</b> {$review['author_name']}\n";
        }
        
        if (!empty($review['review_text'])) {
            $text = mb_substr($review['review_text'], 0, 300);
            if (mb_strlen($review['review_text']) > 300) {
                $text .= "...";
            }
            $message .= "\n<b>Текст отзыва:</b>\n{$text}\n";
        }
        
        if (!empty($review['author_email'])) {
            $message .= "\n<b>Email:</b> {$review['author_email']}";
        }
        if (!empty($review['author_phone'])) {
            $message .= "\n<b>Телефон:</b> {$review['author_phone']}";
        }
        
        $message .= "\n\n<b>Дата:</b> " . current_time('d.m.Y H:i');
        
        return $this->send_message($message);
    }
}
