<?php
if (!defined('ABSPATH')) {
    exit;
}

if (isset($_POST['yareviews_settings_submit'])) {
    check_admin_referer('yareviews_settings_action', 'yareviews_settings_nonce');
    
    $settings = [
        'yandex_org_id' => sanitize_text_field($_POST['yandex_org_id'] ?? ''),
        'yandex_api_key' => sanitize_text_field($_POST['yandex_api_key'] ?? ''),
        'telegram_bot_token' => sanitize_text_field($_POST['telegram_bot_token'] ?? ''),
        'telegram_chat_id' => sanitize_text_field($_POST['telegram_chat_id'] ?? ''),
        'qr_redirect_url' => esc_url_raw($_POST['qr_redirect_url'] ?? ''),
        'enable_telegram_notifications' => isset($_POST['enable_telegram_notifications']),
        'enable_positive_notifications' => isset($_POST['enable_positive_notifications']),
        'privacy_policy_url' => esc_url_raw($_POST['privacy_policy_url'] ?? ''),
        'public_offer_url' => esc_url_raw($_POST['public_offer_url'] ?? ''),
        'thank_you_page_url' => esc_url_raw($_POST['thank_you_page_url'] ?? ''),
        'form_name_required' => isset($_POST['form_name_required']),
        'form_email_required' => isset($_POST['form_email_required']),
        'form_phone_required' => isset($_POST['form_phone_required']),
        'form_background' => sanitize_text_field($_POST['form_background'] ?? 'gradient'),
        'form_bg_color1' => sanitize_hex_color($_POST['form_bg_color1'] ?? '#667eea'),
        'form_bg_color2' => sanitize_hex_color($_POST['form_bg_color2'] ?? '#764ba2'),
    ];
    
    update_option('yareviews_settings', $settings);
    echo '<div class="notice notice-success"><p>' . esc_html__('Настройки сохранены!', 'yareviews') . '</p></div>';
}

$settings = get_option('yareviews_settings', []);
?>

<div class="wrap yareviews-admin">
    <h1 class="yareviews-title">
        <span class="dashicons dashicons-admin-settings"></span>
        <?php echo esc_html__('Настройки YAREVIEWS', 'yareviews'); ?>
    </h1>
    
    <form method="post" action="" class="yareviews-form">
        <?php wp_nonce_field('yareviews_settings_action', 'yareviews_settings_nonce'); ?>
        
        <div class="yareviews-settings-section">
            <h2><?php echo esc_html__('Яндекс Карты / Справочник', 'yareviews'); ?></h2>
            <p class="description"><?php echo esc_html__('Настройте подключение к API Яндекс для автоматической синхронизации отзывов', 'yareviews'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="yandex_org_id"><?php echo esc_html__('ID организации Яндекс', 'yareviews'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="yandex_org_id" 
                               name="yandex_org_id" 
                               value="<?php echo esc_attr($settings['yandex_org_id'] ?? ''); ?>" 
                               class="regular-text">
                        <p class="description"><?php echo esc_html__('Введите ID вашей организации из Яндекс Карт', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="yandex_api_key"><?php echo esc_html__('API ключ Яндекс', 'yareviews'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="yandex_api_key" 
                               name="yandex_api_key" 
                               value="<?php echo esc_attr($settings['yandex_api_key'] ?? ''); ?>" 
                               class="regular-text">
                        <p class="description"><?php echo esc_html__('API ключ для доступа к данным организации', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="qr_redirect_url"><?php echo esc_html__('URL для положительных отзывов', 'yareviews'); ?></label>
                    </th>
                    <td>
                        <input type="url" 
                               id="qr_redirect_url" 
                               name="qr_redirect_url" 
                               value="<?php echo esc_url($settings['qr_redirect_url'] ?? ''); ?>" 
                               class="regular-text" 
                               placeholder="https://yandex.ru/maps/org/...">
                        <p class="description"><?php echo esc_html__('URL на страницу отзывов в Яндекс Картах (для редиректа с оценкой 4-5 звезд)', 'yareviews'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="yareviews-settings-section">
            <h2><?php echo esc_html__('Telegram уведомления', 'yareviews'); ?></h2>
            <p class="description"><?php echo esc_html__('Настройте Telegram бота для получения мгновенных уведомлений о новых отзывах и претензиях', 'yareviews'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="telegram_bot_token"><?php echo esc_html__('Токен Telegram бота', 'yareviews'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="telegram_bot_token" 
                               name="telegram_bot_token" 
                               value="<?php echo esc_attr($settings['telegram_bot_token'] ?? ''); ?>" 
                               class="regular-text">
                        <p class="description"><?php echo esc_html__('Получите токен у @BotFather в Telegram', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="telegram_chat_id"><?php echo esc_html__('Chat ID', 'yareviews'); ?></label>
                    </th>
                    <td>
                        <input type="text" 
                               id="telegram_chat_id" 
                               name="telegram_chat_id" 
                               value="<?php echo esc_attr($settings['telegram_chat_id'] ?? ''); ?>" 
                               class="regular-text">
                        <p class="description"><?php echo esc_html__('ID чата или канала для отправки уведомлений', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo esc_html__('Включить уведомления', 'yareviews'); ?>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" 
                                   name="enable_telegram_notifications" 
                                   value="1" 
                                   <?php checked(!empty($settings['enable_telegram_notifications'])); ?>>
                            <?php echo esc_html__('Отправлять уведомления о негативных отзывах (1-3 звезды)', 'yareviews'); ?>
                        </label>
                        <br><br>
                        <label>
                            <input type="checkbox" 
                                   name="enable_positive_notifications" 
                                   value="1" 
                                   <?php checked(!empty($settings['enable_positive_notifications'])); ?>>
                            <?php echo esc_html__('Отправлять уведомления о положительных отзывах (4-5 звезд)', 'yareviews'); ?>
                        </label>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="yareviews-settings-section">
            <h2><?php echo esc_html__('Настройки формы отзывов', 'yareviews'); ?></h2>
            <p class="description"><?php echo esc_html__('Настройте ссылки на юридические документы и страницу благодарности', 'yareviews'); ?></p>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="privacy_policy_url"><?php echo esc_html__('Политика конфиденциальности', 'yareviews'); ?></label>
                    </th>
                    <td>
                        <input type="url" 
                               id="privacy_policy_url" 
                               name="privacy_policy_url" 
                               value="<?php echo esc_url($settings['privacy_policy_url'] ?? ''); ?>" 
                               class="regular-text" 
                               placeholder="https://example.com/privacy-policy">
                        <p class="description"><?php echo esc_html__('URL страницы с политикой конфиденциальности', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="public_offer_url"><?php echo esc_html__('Публичная оферта', 'yareviews'); ?></label>
                    </th>
                    <td>
                        <input type="url" 
                               id="public_offer_url" 
                               name="public_offer_url" 
                               value="<?php echo esc_url($settings['public_offer_url'] ?? ''); ?>" 
                               class="regular-text" 
                               placeholder="https://example.com/offer">
                        <p class="description"><?php echo esc_html__('URL страницы с публичной офертой', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="thank_you_page_url"><?php echo esc_html__('Страница благодарности', 'yareviews'); ?></label>
                    </th>
                    <td>
                        <input type="url" 
                               id="thank_you_page_url" 
                               name="thank_you_page_url" 
                               value="<?php echo esc_url($settings['thank_you_page_url'] ?? ''); ?>" 
                               class="regular-text" 
                               placeholder="https://example.com/thank-you">
                        <p class="description"><?php echo esc_html__('URL страницы благодарности после отправки негативного отзыва. Если не указана, будет показано стандартное сообщение.', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo esc_html__('Обязательные поля формы', 'yareviews'); ?>
                    </th>
                    <td>
                        <label style="display: block; margin-bottom: 10px;">
                            <input type="checkbox" 
                                   name="form_name_required" 
                                   value="1" 
                                   <?php checked(!empty($settings['form_name_required'])); ?>>
                            <?php echo esc_html__('Имя обязательно', 'yareviews'); ?>
                        </label>
                        <label style="display: block; margin-bottom: 10px;">
                            <input type="checkbox" 
                                   name="form_email_required" 
                                   value="1" 
                                   <?php checked(!empty($settings['form_email_required'])); ?>>
                            <?php echo esc_html__('Email обязательно', 'yareviews'); ?>
                        </label>
                        <label style="display: block;">
                            <input type="checkbox" 
                                   name="form_phone_required" 
                                   value="1" 
                                   <?php checked(!empty($settings['form_phone_required'])); ?>>
                            <?php echo esc_html__('Телефон обязательно', 'yareviews'); ?>
                        </label>
                        <p class="description"><?php echo esc_html__('Отметьте, какие поля должны быть обязательными для заполнения. Поле "Текст отзыва" всегда обязательно.', 'yareviews'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php echo esc_html__('Фон формы оценки', 'yareviews'); ?>
                    </th>
                    <td>
                        <label style="display: block; margin-bottom: 10px;">
                            <input type="radio" 
                                   name="form_background" 
                                   value="gradient" 
                                   <?php checked(($settings['form_background'] ?? 'gradient'), 'gradient'); ?>>
                            <?php echo esc_html__('Градиент', 'yareviews'); ?>
                        </label>
                        <div style="margin: 10px 0 20px 25px;">
                            <label style="display: inline-block; margin-right: 20px;">
                                <?php echo esc_html__('Цвет 1:', 'yareviews'); ?>
                                <input type="color" 
                                       name="form_bg_color1" 
                                       value="<?php echo esc_attr($settings['form_bg_color1'] ?? '#667eea'); ?>">
                            </label>
                            <label style="display: inline-block;">
                                <?php echo esc_html__('Цвет 2:', 'yareviews'); ?>
                                <input type="color" 
                                       name="form_bg_color2" 
                                       value="<?php echo esc_attr($settings['form_bg_color2'] ?? '#764ba2'); ?>">
                            </label>
                        </div>
                        <label style="display: block;">
                            <input type="radio" 
                                   name="form_background" 
                                   value="solid" 
                                   <?php checked(($settings['form_background'] ?? 'gradient'), 'solid'); ?>>
                            <?php echo esc_html__('Однотонный (будет использован Цвет 1)', 'yareviews'); ?>
                        </label>
                        <p class="description"><?php echo esc_html__('Выберите тип фона для формы оценки (/yareviews-rate/)', 'yareviews'); ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <p class="submit">
            <input type="submit" 
                   name="yareviews_settings_submit" 
                   class="button button-primary" 
                   value="<?php echo esc_attr__('Сохранить настройки', 'yareviews'); ?>">
        </p>
    </form>
    
    <div class="yareviews-qr-section">
        <h2><?php echo esc_html__('QR-код для сбора отзывов', 'yareviews'); ?></h2>
        <p class="description"><?php echo esc_html__('Разместите этот QR-код в вашем офисе, чтобы клиенты могли быстро оставить отзыв', 'yareviews'); ?></p>
        
        <div class="yareviews-qr-display">
            <img src="<?php echo esc_url(\YAReviews\QR_Generator::get_qr_code_png(300)); ?>" 
                 alt="QR Code" 
                 class="yareviews-qr-image">
            <div class="yareviews-qr-info">
                <p><strong><?php echo esc_html__('URL формы:', 'yareviews'); ?></strong></p>
                <code><?php echo esc_url(\YAReviews\QR_Generator::generate_qr_url()); ?></code>
                <br><br>
                <a href="<?php echo esc_url(\YAReviews\QR_Generator::get_qr_code_png(1000)); ?>" 
                   download="yareviews-qr-code.png" 
                   class="button">
                    <?php echo esc_html__('Скачать QR-код (PNG)', 'yareviews'); ?>
                </a>
            </div>
        </div>
    </div>
</div>
