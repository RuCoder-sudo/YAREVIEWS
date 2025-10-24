<?php
namespace YAReviews;

if (!defined('ABSPATH')) {
    exit;
}

class Database {
    
    private $wpdb;
    private $reviews_table;
    private $complaints_table;
    
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->reviews_table = $wpdb->prefix . 'yareviews_reviews';
        $this->complaints_table = $wpdb->prefix . 'yareviews_complaints';
    }
    
    public function get_reviews($args = []) {
        $defaults = [
            'limit' => 10,
            'offset' => 0,
            'status' => 'published',
            'min_rating' => 0,
            'orderby' => 'review_date',
            'order' => 'DESC'
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        $allowed_orderby = ['id', 'review_date', 'rating', 'author_name', 'created_at'];
        $orderby = in_array($args['orderby'], $allowed_orderby, true) ? $args['orderby'] : 'review_date';
        
        $order = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';
        
        $where = ['status = %s'];
        $where_values = [$args['status']];
        
        if ($args['min_rating'] > 0) {
            $where[] = 'rating >= %d';
            $where_values[] = $args['min_rating'];
        }
        
        $where_clause = implode(' AND ', $where);
        
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->reviews_table} 
            WHERE {$where_clause} 
            ORDER BY {$orderby} {$order} 
            LIMIT %d OFFSET %d",
            array_merge($where_values, [$args['limit'], $args['offset']])
        );
        
        return $this->wpdb->get_results($query);
    }
    
    public function get_review_by_id($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare("SELECT * FROM {$this->reviews_table} WHERE id = %d", $id)
        );
    }
    
    public function insert_review($data) {
        $defaults = [
            'author_name' => '',
            'author_avatar' => '',
            'rating' => 5,
            'review_text' => '',
            'review_date' => current_time('mysql'),
            'source' => 'manual',
            'yandex_id' => null,
            'status' => 'published'
        ];
        
        $data = wp_parse_args($data, $defaults);
        
        $result = $this->wpdb->insert(
            $this->reviews_table,
            $data,
            ['%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s']
        );
        
        return $result ? $this->wpdb->insert_id : false;
    }
    
    public function update_review($id, $data) {
        return $this->wpdb->update(
            $this->reviews_table,
            $data,
            ['id' => $id],
            null,
            ['%d']
        );
    }
    
    public function delete_review($id) {
        return $this->wpdb->delete(
            $this->reviews_table,
            ['id' => $id],
            ['%d']
        );
    }
    
    public function get_complaints($args = []) {
        $defaults = [
            'limit' => 10,
            'offset' => 0,
            'status' => 'new',
            'orderby' => 'created_at',
            'order' => 'DESC'
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        $allowed_orderby = ['id', 'created_at', 'rating', 'status'];
        $orderby = in_array($args['orderby'], $allowed_orderby, true) ? $args['orderby'] : 'created_at';
        
        $order = strtoupper($args['order']) === 'ASC' ? 'ASC' : 'DESC';
        
        $where = $args['status'] !== 'all' ? 'WHERE status = %s' : '';
        $where_values = $args['status'] !== 'all' ? [$args['status']] : [];
        
        $query = $this->wpdb->prepare(
            "SELECT * FROM {$this->complaints_table} 
            {$where} 
            ORDER BY {$orderby} {$order} 
            LIMIT %d OFFSET %d",
            array_merge($where_values, [$args['limit'], $args['offset']])
        );
        
        return $this->wpdb->get_results($query);
    }
    
    public function insert_complaint($data) {
        $defaults = [
            'author_name' => null,
            'author_email' => null,
            'author_phone' => null,
            'rating' => 1,
            'complaint_text' => '',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'status' => 'new',
            'notified' => 0
        ];
        
        $data = wp_parse_args($data, $defaults);
        
        $result = $this->wpdb->insert(
            $this->complaints_table,
            $data,
            ['%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d']
        );
        
        return $result ? $this->wpdb->insert_id : false;
    }
    
    public function update_complaint($id, $data) {
        return $this->wpdb->update(
            $this->complaints_table,
            $data,
            ['id' => $id],
            null,
            ['%d']
        );
    }
    
    public function get_stats() {
        $total_reviews = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->reviews_table} WHERE status = 'published'");
        $avg_rating = $this->wpdb->get_var("SELECT AVG(rating) FROM {$this->reviews_table} WHERE status = 'published'");
        $total_complaints = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->complaints_table}");
        $new_complaints = $this->wpdb->get_var("SELECT COUNT(*) FROM {$this->complaints_table} WHERE status = 'new'");
        
        return [
            'total_reviews' => (int) $total_reviews,
            'avg_rating' => round((float) $avg_rating, 2),
            'total_complaints' => (int) $total_complaints,
            'new_complaints' => (int) $new_complaints
        ];
    }
}
