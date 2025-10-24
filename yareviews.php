<?php
/**
 * Plugin Name: YAREVIEWS
 * Plugin URI: https://рукодер.рф/
 * Description: Профессиональный плагин для работы с отзывами из Яндекс Карт и Яндекс Справочника с системой перехвата негативных отзывов
 * Version: 1.0.0
 * Author: RUCODER
 * Author URI: https://рукодер.рф/
 * Text Domain: yareviews
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * ВАЖНО - АВТОРСКИЕ ПРАВА!
 * =========================
 * Этот плагин является интеллектуальной собственностью разработчика.
 * Распространение, копирование, модификация, продажа данного плагина
 * без письменного разрешения владельца строго запрещена.
 * Нарушение авторских прав преследуется по закону.
 * 
 * Разработчик: RUCODER - Разработка сайтов.
 * Сайт: https://рукодер.рф/
 * Телеграм: https://t.me/RussCoder
 * VK: https://vk.com/rucoderweb
 * Instagram: https://www.instagram.com/rucoder.web/
 * Email: rucoder.rf@yandex.ru
 * 
 * По всем вопросам и для заказа разработки сайтов
 * обращайтесь по контактным данным выше.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('YAREVIEWS_VERSION', '1.0.0');
define('YAREVIEWS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('YAREVIEWS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('YAREVIEWS_PLUGIN_FILE', __FILE__);
define('YAREVIEWS_PLUGIN_BASENAME', plugin_basename(__FILE__));

require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-autoloader.php';
YAReviews\Autoloader::register();

function yareviews_activate() {
    require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-activator.php';
    YAReviews\Activator::activate();
}

function yareviews_deactivate() {
    require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-deactivator.php';
    YAReviews\Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'yareviews_activate');
register_deactivation_hook(__FILE__, 'yareviews_deactivate');

function yareviews_init() {
    require_once YAREVIEWS_PLUGIN_DIR . 'includes/class-yareviews-main.php';
    $plugin = new YAReviews\Main();
    $plugin->run();
}

add_action('plugins_loaded', 'yareviews_init');
