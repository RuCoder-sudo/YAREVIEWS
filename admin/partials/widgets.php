<?php
if (!defined('ABSPATH')) {
    exit;
}

if (isset($_POST['yareviews_widgets_submit'])) {
    check_admin_referer('yareviews_widgets_action', 'yareviews_widgets_nonce');
    
    $widget_slider = [
        'enabled' => isset($_POST['slider_enabled']),
        'theme' => sanitize_text_field($_POST['slider_theme'] ?? 'light'),
        'slides_per_view' => (int) ($_POST['slider_slides'] ?? 3),
        'autoplay' => isset($_POST['slider_autoplay']),
        'autoplay_delay' => (int) ($_POST['slider_autoplay_delay'] ?? 3000),
        'show_navigation' => isset($_POST['slider_navigation']),
        'show_pagination' => isset($_POST['slider_pagination']),
        'show_avatar' => isset($_POST['slider_show_avatar']),
        'show_rating' => isset($_POST['slider_show_rating']),
        'show_date' => isset($_POST['slider_show_date']),
        'accent_color' => sanitize_hex_color($_POST['slider_accent_color'] ?? '#FFD700'),
    ];
    
    $widget_badge = [
        'enabled' => isset($_POST['badge_enabled']),
        'theme' => sanitize_text_field($_POST['badge_theme'] ?? 'light'),
        'position' => sanitize_text_field($_POST['badge_position'] ?? 'bottom-right'),
        'text' => sanitize_text_field($_POST['badge_text'] ?? 'Наши отзывы'),
        'accent_color' => sanitize_hex_color($_POST['badge_accent_color'] ?? '#FFD700'),
    ];
    
    $widget_grid = [
        'enabled' => isset($_POST['grid_enabled']),
        'theme' => sanitize_text_field($_POST['grid_theme'] ?? 'light'),
        'columns' => (int) ($_POST['grid_columns'] ?? 3),
        'show_avatar' => isset($_POST['grid_show_avatar']),
        'show_rating' => isset($_POST['grid_show_rating']),
        'show_date' => isset($_POST['grid_show_date']),
        'accent_color' => sanitize_hex_color($_POST['grid_accent_color'] ?? '#FFD700'),
        'enable_load_more' => isset($_POST['grid_enable_load_more']),
        'initial_count' => (int) ($_POST['grid_initial_count'] ?? 6),
        'load_more_count' => (int) ($_POST['grid_load_more_count'] ?? 6),
    ];
    
    update_option('yareviews_widget_slider', $widget_slider);
    update_option('yareviews_widget_badge', $widget_badge);
    update_option('yareviews_widget_grid', $widget_grid);
    
    echo '<div class="notice notice-success"><p>' . esc_html__('Настройки виджетов сохранены!', 'yareviews') . '</p></div>';
}

$widget_slider = get_option('yareviews_widget_slider', []);
$widget_badge = get_option('yareviews_widget_badge', []);
$widget_grid = get_option('yareviews_widget_grid', []);
?>

<div class="wrap yareviews-admin">
    <h1 class="yareviews-title">
        <span class="dashicons dashicons-admin-customizer"></span>
        <?php echo esc_html__('Настройки виджетов', 'yareviews'); ?>
    </h1>
    
    <form method="post" action="" class="yareviews-form">
        <?php wp_nonce_field('yareviews_widgets_action', 'yareviews_widgets_nonce'); ?>
        
        <div class="yareviews-widget-tabs">
            <button type="button" class="yareviews-tab-button active" data-tab="slider">
                <?php echo esc_html__('Слайдер', 'yareviews'); ?>
            </button>
            <button type="button" class="yareviews-tab-button" data-tab="badge">
                <?php echo esc_html__('Плавающий бейдж', 'yareviews'); ?>
            </button>
            <button type="button" class="yareviews-tab-button" data-tab="grid">
                <?php echo esc_html__('Плитка/Сетка', 'yareviews'); ?>
            </button>
        </div>
        
        <div id="slider-tab" class="yareviews-tab-content active">
            <h2><?php echo esc_html__('Виджет: Слайдер отзывов', 'yareviews'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Включить виджет', 'yareviews'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="slider_enabled" value="1" <?php checked(!empty($widget_slider['enabled'])); ?>>
                            <?php echo esc_html__('Включить слайдер отзывов', 'yareviews'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Тема', 'yareviews'); ?></th>
                    <td>
                        <select name="slider_theme">
                            <option value="light" <?php selected($widget_slider['theme'] ?? 'light', 'light'); ?>>
                                <?php echo esc_html__('Светлая', 'yareviews'); ?>
                            </option>
                            <option value="dark" <?php selected($widget_slider['theme'] ?? '', 'dark'); ?>>
                                <?php echo esc_html__('Тёмная', 'yareviews'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Количество слайдов', 'yareviews'); ?></th>
                    <td>
                        <input type="range" 
                               name="slider_slides" 
                               min="1" 
                               max="4" 
                               value="<?php echo esc_attr($widget_slider['slides_per_view'] ?? 3); ?>" 
                               class="yareviews-range-slider">
                        <span class="range-value"><?php echo esc_html($widget_slider['slides_per_view'] ?? 3); ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Автопрокрутка', 'yareviews'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="slider_autoplay" value="1" <?php checked(!empty($widget_slider['autoplay'])); ?>>
                            <?php echo esc_html__('Включить автопрокрутку', 'yareviews'); ?>
                        </label>
                        <br><br>
                        <label>
                            <?php echo esc_html__('Интервал (мс):', 'yareviews'); ?>
                            <input type="number" 
                                   name="slider_autoplay_delay" 
                                   value="<?php echo esc_attr($widget_slider['autoplay_delay'] ?? 3000); ?>" 
                                   min="1000" 
                                   step="500" 
                                   class="small-text">
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Навигация', 'yareviews'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="slider_navigation" value="1" <?php checked(!empty($widget_slider['show_navigation'])); ?>>
                            <?php echo esc_html__('Показывать стрелки', 'yareviews'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="slider_pagination" value="1" <?php checked(!empty($widget_slider['show_pagination'])); ?>>
                            <?php echo esc_html__('Показывать точки-индикаторы', 'yareviews'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Элементы отображения', 'yareviews'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="slider_show_avatar" value="1" <?php checked(!empty($widget_slider['show_avatar'])); ?>>
                            <?php echo esc_html__('Аватар автора', 'yareviews'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="slider_show_rating" value="1" <?php checked(!empty($widget_slider['show_rating'])); ?>>
                            <?php echo esc_html__('Звезды рейтинга', 'yareviews'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="slider_show_date" value="1" <?php checked(!empty($widget_slider['show_date'])); ?>>
                            <?php echo esc_html__('Дата отзыва', 'yareviews'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Акцентный цвет', 'yareviews'); ?></th>
                    <td>
                        <input type="text" 
                               name="slider_accent_color" 
                               value="<?php echo esc_attr($widget_slider['accent_color'] ?? '#FFD700'); ?>" 
                               class="yareviews-color-picker">
                    </td>
                </tr>
            </table>
            
            <div class="yareviews-shortcode-info">
                <h3><?php echo esc_html__('Использование:', 'yareviews'); ?></h3>
                <p><?php echo esc_html__('Вставьте этот шорткод на любую страницу:', 'yareviews'); ?></p>
                <code>[yareviews type="slider" count="5" min_rating="4"]</code>
            </div>
        </div>
        
        <div id="badge-tab" class="yareviews-tab-content">
            <h2><?php echo esc_html__('Виджет: Плавающий бейдж', 'yareviews'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Включить виджет', 'yareviews'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="badge_enabled" value="1" <?php checked(!empty($widget_badge['enabled'])); ?>>
                            <?php echo esc_html__('Включить плавающий бейдж', 'yareviews'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Тема', 'yareviews'); ?></th>
                    <td>
                        <select name="badge_theme">
                            <option value="light" <?php selected($widget_badge['theme'] ?? 'light', 'light'); ?>>
                                <?php echo esc_html__('Светлая', 'yareviews'); ?>
                            </option>
                            <option value="dark" <?php selected($widget_badge['theme'] ?? '', 'dark'); ?>>
                                <?php echo esc_html__('Тёмная', 'yareviews'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Позиция', 'yareviews'); ?></th>
                    <td>
                        <select name="badge_position">
                            <option value="bottom-left" <?php selected($widget_badge['position'] ?? '', 'bottom-left'); ?>>
                                <?php echo esc_html__('Снизу слева', 'yareviews'); ?>
                            </option>
                            <option value="bottom-right" <?php selected($widget_badge['position'] ?? 'bottom-right', 'bottom-right'); ?>>
                                <?php echo esc_html__('Снизу справа', 'yareviews'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Текст бейджа', 'yareviews'); ?></th>
                    <td>
                        <input type="text" 
                               name="badge_text" 
                               value="<?php echo esc_attr($widget_badge['text'] ?? 'Наши отзывы'); ?>" 
                               class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Акцентный цвет', 'yareviews'); ?></th>
                    <td>
                        <input type="text" 
                               name="badge_accent_color" 
                               value="<?php echo esc_attr($widget_badge['accent_color'] ?? '#FFD700'); ?>" 
                               class="yareviews-color-picker">
                    </td>
                </tr>
            </table>
            
            <div class="yareviews-shortcode-info">
                <h3><?php echo esc_html__('Использование:', 'yareviews'); ?></h3>
                <p><?php echo esc_html__('Вставьте этот шорткод (обычно в footer.php темы):', 'yareviews'); ?></p>
                <code>[yareviews type="badge" position="bottom-right"]</code>
            </div>
        </div>
        
        <div id="grid-tab" class="yareviews-tab-content">
            <h2><?php echo esc_html__('Виджет: Плитка/Сетка', 'yareviews'); ?></h2>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Включить виджет', 'yareviews'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="grid_enabled" value="1" <?php checked(!empty($widget_grid['enabled'])); ?>>
                            <?php echo esc_html__('Включить сетку отзывов', 'yareviews'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Тема', 'yareviews'); ?></th>
                    <td>
                        <select name="grid_theme">
                            <option value="light" <?php selected($widget_grid['theme'] ?? 'light', 'light'); ?>>
                                <?php echo esc_html__('Светлая', 'yareviews'); ?>
                            </option>
                            <option value="dark" <?php selected($widget_grid['theme'] ?? '', 'dark'); ?>>
                                <?php echo esc_html__('Тёмная', 'yareviews'); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Количество колонок', 'yareviews'); ?></th>
                    <td>
                        <select name="grid_columns">
                            <option value="2" <?php selected($widget_grid['columns'] ?? '', 2); ?>>2</option>
                            <option value="3" <?php selected($widget_grid['columns'] ?? 3, 3); ?>>3</option>
                            <option value="4" <?php selected($widget_grid['columns'] ?? '', 4); ?>>4</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Элементы отображения', 'yareviews'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="grid_show_avatar" value="1" <?php checked(!empty($widget_grid['show_avatar'])); ?>>
                            <?php echo esc_html__('Аватар автора', 'yareviews'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="grid_show_rating" value="1" <?php checked(!empty($widget_grid['show_rating'])); ?>>
                            <?php echo esc_html__('Звезды рейтинга', 'yareviews'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="grid_show_date" value="1" <?php checked(!empty($widget_grid['show_date'])); ?>>
                            <?php echo esc_html__('Дата отзыва', 'yareviews'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Акцентный цвет', 'yareviews'); ?></th>
                    <td>
                        <input type="text" 
                               name="grid_accent_color" 
                               value="<?php echo esc_attr($widget_grid['accent_color'] ?? '#FFD700'); ?>" 
                               class="yareviews-color-picker">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Кнопка "Загрузить ещё"', 'yareviews'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="grid_enable_load_more" value="1" <?php checked(!empty($widget_grid['enable_load_more'])); ?>>
                            <?php echo esc_html__('Включить кнопку "Загрузить ещё"', 'yareviews'); ?>
                        </label>
                        <p class="description"><?php echo esc_html__('Позволяет загружать отзывы порциями при нажатии на кнопку', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Начальное количество', 'yareviews'); ?></th>
                    <td>
                        <input type="number" 
                               name="grid_initial_count" 
                               value="<?php echo esc_attr($widget_grid['initial_count'] ?? 6); ?>" 
                               min="1" 
                               max="50" 
                               class="small-text">
                        <p class="description"><?php echo esc_html__('Сколько отзывов показывать изначально', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Загружать при клике', 'yareviews'); ?></th>
                    <td>
                        <input type="number" 
                               name="grid_load_more_count" 
                               value="<?php echo esc_attr($widget_grid['load_more_count'] ?? 6); ?>" 
                               min="1" 
                               max="50" 
                               class="small-text">
                        <p class="description"><?php echo esc_html__('Сколько отзывов загружать при нажатии на "Загрузить ещё"', 'yareviews'); ?></p>
                    </td>
                </tr>
            </table>
            
            <div class="yareviews-shortcode-info">
                <h3><?php echo esc_html__('Использование:', 'yareviews'); ?></h3>
                <p><?php echo esc_html__('Вставьте этот шорткод на любую страницу:', 'yareviews'); ?></p>
                <code>[yareviews type="grid" count="6" columns="3" load_more="true"]</code>
            </div>
        </div>
        
        <p class="submit">
            <input type="submit" 
                   name="yareviews_widgets_submit" 
                   class="button button-primary" 
                   value="<?php echo esc_attr__('Сохранить настройки виджетов', 'yareviews'); ?>">
        </p>
    </form>
</div>
