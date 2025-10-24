<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap yareviews-admin">
    <h1 class="yareviews-title">
        <span class="dashicons dashicons-editor-help"></span>
        <?php echo esc_html__('Помощь и документация', 'yareviews'); ?>
    </h1>
    
    <div class="yareviews-help-content">
        <div class="help-section">
            <h2><?php echo esc_html__('🗺️ ПОЛНАЯ ИНСТРУКЦИЯ: Подключение к Яндекс Картам', 'yareviews'); ?></h2>
            
            <div class="help-notice" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;">
                <strong>⚠️ ВАЖНО:</strong> Для работы с Яндекс API вам потребуется <strong>два ключа</strong>:
                <ul style="margin: 10px 0 0 20px;">
                    <li><strong>ID организации</strong> - из Яндекс Справочника</li>
                    <li><strong>API ключ</strong> - из Yandex Cloud (бесплатно)</li>
                </ul>
            </div>

            <h3 style="color: #2271b1; margin-top: 30px;">📋 ШАГ 1: Получите ID организации</h3>
            <ol class="help-steps" style="line-height: 1.8;">
                <li>
                    <strong>Откройте Яндекс Справочник</strong>
                    <p>Перейдите на сайт: <a href="https://yandex.ru/business" target="_blank">https://yandex.ru/business</a></p>
                </li>
                <li>
                    <strong>Войдите в аккаунт</strong>
                    <p>Используйте свой Яндекс аккаунт (или создайте новый, если его нет)</p>
                </li>
                <li>
                    <strong>Выберите вашу организацию</strong>
                    <p>Если организации нет, добавьте её нажав "Добавить организацию"</p>
                </li>
                <li>
                    <strong>Найдите ID организации</strong>
                    <p>В адресной строке браузера найдите число после <code>/org/</code></p>
                    <p><strong>Пример:</strong> <code>https://yandex.ru/business/widget/booking/12345678901234567890</code><br>
                    ID организации: <code>12345678901234567890</code></p>
                </li>
                <li>
                    <strong>Скопируйте ID</strong>
                    <p>Это длинное число (около 20 цифр) - сохраните его, он понадобится позже</p>
                </li>
            </ol>

            <h3 style="color: #2271b1; margin-top: 30px;">☁️ ШАГ 2: Создайте API ключ в Yandex Cloud</h3>
            <div class="help-notice" style="background: #d1ecf1; border-left: 4px solid #0c5460; padding: 15px; margin: 15px 0;">
                <strong>ℹ️ Что такое Yandex Cloud?</strong><br>
                Это облачная платформа Яндекса для разработчиков. API ключ нужен для безопасного доступа к данным вашей организации.<br>
                <strong>Бесплатно:</strong> Для работы с отзывами API ключ предоставляется бесплатно!
            </div>

            <ol class="help-steps" style="line-height: 1.8;">
                <li>
                    <strong>Зарегистрируйтесь в Yandex Cloud</strong>
                    <p>Перейдите на: <a href="https://console.cloud.yandex.ru" target="_blank">https://console.cloud.yandex.ru</a></p>
                    <p>Нажмите "Войти" и используйте тот же Яндекс аккаунт</p>
                </li>
                <li>
                    <strong>Примите условия и создайте биллинг-аккаунт</strong>
                    <p>Не переживайте! Для базового использования API это <strong>бесплатно</strong></p>
                    <p>Просто заполните форму регистрации (можно указать физ. лицо)</p>
                </li>
                <li>
                    <strong>Создайте "Облако" (Cloud)</strong>
                    <p>После регистрации система предложит создать облако</p>
                    <p>Название может быть любым, например: "Мой сайт" или "Отзывы"</p>
                </li>
                <li>
                    <strong>Создайте "Каталог" (Folder)</strong>
                    <p>Внутри облака нажмите "Создать каталог"</p>
                    <p>Название: например, "default" или "production"</p>
                </li>
                <li>
                    <strong>Перейдите в раздел "API ключи"</strong>
                    <p>В меню слева найдите: <strong>Service accounts (Сервисные аккаунты)</strong></p>
                    <p>Если его нет, создайте сервисный аккаунт:</p>
                    <ul style="margin: 10px 0 0 20px;">
                        <li>Нажмите "Создать сервисный аккаунт"</li>
                        <li>Имя: любое (например, "yareviews-bot")</li>
                        <li>Роли: выберите <code>editor</code> или <code>viewer</code></li>
                    </ul>
                </li>
                <li>
                    <strong>Создайте API ключ</strong>
                    <p>Откройте созданный сервисный аккаунт</p>
                    <p>Нажмите "Создать новый ключ" → выберите "API ключ"</p>
                    <p><strong>ВАЖНО:</strong> Скопируйте ключ СРАЗУ! Он показывается только один раз!</p>
                </li>
                <li>
                    <strong>Сохраните API ключ</strong>
                    <p>Ключ выглядит примерно так: <code>AQVNxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</code></p>
                    <p>Сохраните его в безопасное место (например, в файл на компьютере)</p>
                </li>
            </ol>

            <h3 style="color: #2271b1; margin-top: 30px;">⚙️ ШАГ 3: Внесите данные в плагин</h3>
            <ol class="help-steps" style="line-height: 1.8;">
                <li>
                    <strong>Откройте настройки плагина</strong>
                    <p>В админке WordPress: <strong>YAREVIEWS → Настройки</strong></p>
                </li>
                <li>
                    <strong>Вставьте ID организации</strong>
                    <p>В поле "ID организации Яндекс" вставьте скопированный ID (20 цифр)</p>
                </li>
                <li>
                    <strong>Вставьте API ключ</strong>
                    <p>В поле "API ключ Яндекс" вставьте ключ из Yandex Cloud</p>
                </li>
                <li>
                    <strong>Укажите URL для положительных отзывов</strong>
                    <p>Это ссылка на страницу отзывов вашей организации в Яндекс Картах</p>
                    <p><strong>Как найти:</strong></p>
                    <ul style="margin: 10px 0 0 20px;">
                        <li>Найдите вашу организацию на <a href="https://yandex.ru/maps" target="_blank">Яндекс Картах</a></li>
                        <li>Откройте карточку организации</li>
                        <li>Нажмите "Оставить отзыв" или "Отзывы"</li>
                        <li>Скопируйте URL из адресной строки</li>
                        <li>Пример: <code>https://yandex.ru/maps/org/nazvanie/123456789/reviews</code></li>
                    </ul>
                </li>
                <li>
                    <strong>Нажмите "Сохранить настройки"</strong>
                </li>
            </ol>

            <h3 style="color: #2271b1; margin-top: 30px;">✅ ШАГ 4: Проверьте подключение</h3>
            <ol class="help-steps">
                <li>Вручную добавьте один тестовый отзыв в разделе "Все отзывы"</li>
                <li>Создайте страницу с шорткодом <code>[yareviews_slider]</code></li>
                <li>Убедитесь, что отзывы отображаются корректно</li>
                <li>Проверьте форму оценки по адресу: <code>ваш-сайт.ru/yareviews-rate/</code></li>
            </ol>

            <div class="help-notice" style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0;">
                <strong>✅ Готово!</strong> Теперь плагин подключен к Яндекс и готов к работе!
            </div>
        </div>
        
        <div class="help-section">
            <h2><?php echo esc_html__('🚀 Быстрый старт', 'yareviews'); ?></h2>
            <ol class="help-steps">
                <li>
                    <strong><?php echo esc_html__('Настройте подключение к Яндекс', 'yareviews'); ?></strong>
                    <p><?php echo esc_html__('Следуйте подробной инструкции выше ☝️', 'yareviews'); ?></p>
                </li>
                <li>
                    <strong><?php echo esc_html__('Добавьте отзывы', 'yareviews'); ?></strong>
                    <p><?php echo esc_html__('В разделе "Все отзывы" нажмите "+ Добавить отзыв вручную" и заполните форму', 'yareviews'); ?></p>
                </li>
                <li>
                    <strong><?php echo esc_html__('Настройте виджеты', 'yareviews'); ?></strong>
                    <p><?php echo esc_html__('В разделе "Виджеты" настройте внешний вид слайдера, бейджа или сетки', 'yareviews'); ?></p>
                </li>
                <li>
                    <strong><?php echo esc_html__('Вставьте на сайт', 'yareviews'); ?></strong>
                    <p><?php echo esc_html__('Используйте шорткоды или Gutenberg блоки для отображения отзывов', 'yareviews'); ?></p>
                </li>
            </ol>
        </div>
        
        <div class="help-section">
            <h2><?php echo esc_html__('📝 Использование шорткодов', 'yareviews'); ?></h2>
            
            <div class="help-shortcode">
                <h3><?php echo esc_html__('Слайдер отзывов', 'yareviews'); ?></h3>
                <code>[yareviews type="slider" count="5" min_rating="4" theme="light"]</code>
                <p class="description">
                    <strong>count</strong> - <?php echo esc_html__('количество отзывов', 'yareviews'); ?><br>
                    <strong>min_rating</strong> - <?php echo esc_html__('минимальный рейтинг (1-5)', 'yareviews'); ?><br>
                    <strong>theme</strong> - <?php echo esc_html__('тема (light/dark)', 'yareviews'); ?>
                </p>
            </div>
            
            <div class="help-shortcode">
                <h3><?php echo esc_html__('Плавающий бейдж', 'yareviews'); ?></h3>
                <code>[yareviews type="badge" position="bottom-right" text="Наши отзывы"]</code>
                <p class="description">
                    <strong>position</strong> - <?php echo esc_html__('позиция (bottom-left/bottom-right)', 'yareviews'); ?><br>
                    <strong>text</strong> - <?php echo esc_html__('текст на кнопке', 'yareviews'); ?>
                </p>
            </div>
            
            <div class="help-shortcode">
                <h3><?php echo esc_html__('Сетка отзывов', 'yareviews'); ?></h3>
                <code>[yareviews type="grid" count="6" columns="3" min_rating="4"]</code>
                <p class="description">
                    <strong>columns</strong> - <?php echo esc_html__('количество колонок (2-4)', 'yareviews'); ?><br>
                    <strong>count</strong> - <?php echo esc_html__('количество отзывов', 'yareviews'); ?>
                </p>
            </div>
        </div>
        
        <div class="help-section">
            <h2><?php echo esc_html__('🤖 Настройка Telegram уведомлений', 'yareviews'); ?></h2>
            <ol class="help-steps">
                <li>
                    <strong><?php echo esc_html__('Создайте бота', 'yareviews'); ?></strong>
                    <p><?php echo esc_html__('Найдите в Telegram @BotFather и создайте нового бота командой /newbot', 'yareviews'); ?></p>
                </li>
                <li>
                    <strong><?php echo esc_html__('Скопируйте токен', 'yareviews'); ?></strong>
                    <p><?php echo esc_html__('BotFather выдаст вам токен вида: 123456789:ABCdefGHIjklMNOpqrsTUVwxyz', 'yareviews'); ?></p>
                </li>
                <li>
                    <strong><?php echo esc_html__('Получите Chat ID', 'yareviews'); ?></strong>
                    <p><?php echo esc_html__('Напишите боту любое сообщение, затем откройте:', 'yareviews'); ?><br>
                    <code>https://api.telegram.org/bot&lt;ВАШ_ТОКЕН&gt;/getUpdates</code></p>
                </li>
                <li>
                    <strong><?php echo esc_html__('Внесите данные', 'yareviews'); ?></strong>
                    <p><?php echo esc_html__('В разделе "Настройки" вставьте токен и Chat ID, включите уведомления', 'yareviews'); ?></p>
                </li>
            </ol>
        </div>
        
        <div class="help-section">
            <h2><?php echo esc_html__('🔄 Система перехвата негатива', 'yareviews'); ?></h2>
            <p><?php echo esc_html__('YAREVIEWS автоматически фильтрует отзывы по рейтингу:', 'yareviews'); ?></p>
            <ul class="help-list">
                <li><strong>⭐⭐⭐⭐⭐ 4-5 звезд:</strong> <?php echo esc_html__('Клиент автоматически перенаправляется на Яндекс Карты для публикации положительного отзыва', 'yareviews'); ?></li>
                <li><strong>⭐⭐⭐ 1-3 звезды:</strong> <?php echo esc_html__('Клиент видит форму для анонимной претензии. Отзыв НЕ публикуется на Яндекс, а сохраняется в разделе "Претензии"', 'yareviews'); ?></li>
                <li><strong>📱 Telegram:</strong> <?php echo esc_html__('Вы мгновенно получаете уведомление о негативном отзыве и можете оперативно связаться с клиентом', 'yareviews'); ?></li>
            </ul>
        </div>
        
        <div class="help-section">
            <h2><?php echo esc_html__('📲 Использование QR-кода', 'yareviews'); ?></h2>
            <p><?php echo esc_html__('QR-код генерируется автоматически в разделе "Настройки". Вы можете:', 'yareviews'); ?></p>
            <ul class="help-list">
                <li><?php echo esc_html__('Скачать QR-код в высоком разрешении', 'yareviews'); ?></li>
                <li><?php echo esc_html__('Распечатать и разместить в офисе, на стойке ресепшн, в зале ожидания', 'yareviews'); ?></li>
                <li><?php echo esc_html__('Добавить на визитки, флаеры, чеки', 'yareviews'); ?></li>
            </ul>
            <p><?php echo esc_html__('Клиенты сканируют QR-код и попадают на форму оценки, где происходит "умная" фильтрация.', 'yareviews'); ?></p>
        </div>
        
        <div class="help-section">
            <h2><?php echo esc_html__('❓ Частые вопросы', 'yareviews'); ?></h2>
            
            <div class="help-faq">
                <h4><?php echo esc_html__('Как добавить отзывы на страницу?', 'yareviews'); ?></h4>
                <p><?php echo esc_html__('Используйте шорткод [yareviews type="slider"] в редакторе страницы или добавьте Gutenberg блок "YAREVIEWS Slider"', 'yareviews'); ?></p>
            </div>
            
            <div class="help-faq">
                <h4><?php echo esc_html__('Можно ли изменить цвета виджетов?', 'yareviews'); ?></h4>
                <p><?php echo esc_html__('Да! В разделе "Виджеты" для каждого типа есть настройка "Акцентный цвет" с color picker', 'yareviews'); ?></p>
            </div>
            
            <div class="help-faq">
                <h4><?php echo esc_html__('Как узнать, что пришла новая претензия?', 'yareviews'); ?></h4>
                <p><?php echo esc_html__('Настройте Telegram уведомления в разделе "Настройки". Вы будете получать мгновенные сообщения о каждой новой претензии', 'yareviews'); ?></p>
            </div>
            
            <div class="help-faq">
                <h4><?php echo esc_html__('Можно ли отображать только 5-звездочные отзывы?', 'yareviews'); ?></h4>
                <p><?php echo esc_html__('Да! В шорткоде укажите параметр min_rating="5": [yareviews type="slider" min_rating="5"]', 'yareviews'); ?></p>
            </div>
        </div>
        
        <div class="help-section help-support">
            <h2><?php echo esc_html__('💬 Нужна помощь?', 'yareviews'); ?></h2>
            <p><?php echo esc_html__('Если у вас возникли вопросы или проблемы с плагином, свяжитесь с нами:', 'yareviews'); ?></p>
            
            <div style="background: #f0f9ff; border-left: 4px solid #0284c7; padding: 20px; margin: 20px 0; border-radius: 4px;">
                <h3 style="margin-top: 0; color: #0284c7;">
                    <span class="dashicons dashicons-admin-users" style="font-size: 24px; width: 24px; height: 24px;"></span>
                    RUCODER - Разработка сайтов
                </h3>
                <p style="margin: 15px 0;">
                    <strong>🌐 Сайт:</strong> <a href="https://рукодер.рф/" target="_blank">https://рукодер.рф/</a><br>
                    <strong>📱 Телеграм:</strong> <a href="https://t.me/RussCoder" target="_blank">https://t.me/RussCoder</a><br>
                    <strong>👥 VK:</strong> <a href="https://vk.com/rucoderweb" target="_blank">https://vk.com/rucoderweb</a><br>
                    <strong>📸 Instagram:</strong> <a href="https://www.instagram.com/rucoder.web/" target="_blank">@rucoder.web</a><br>
                    <strong>📧 Email:</strong> <a href="mailto:rucoder.rf@yandex.ru">rucoder.rf@yandex.ru</a>
                </p>
                <p style="margin-bottom: 0; font-size: 13px; color: #555;">
                    <em><?php echo esc_html__('По всем вопросам и для заказа разработки сайтов обращайтесь по контактным данным выше.', 'yareviews'); ?></em>
                </p>
            </div>
            
            <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
                <h4 style="margin-top: 0; color: #856404;">
                    <span class="dashicons dashicons-warning" style="color: #ffc107;"></span>
                    <?php echo esc_html__('ВАЖНО - Авторские права', 'yareviews'); ?>
                </h4>
                <p style="margin-bottom: 0; font-size: 13px; line-height: 1.6;">
                    <?php echo esc_html__('Этот плагин является интеллектуальной собственностью разработчика RUCODER. Распространение, копирование, модификация, продажа данного плагина без письменного разрешения владельца строго запрещена. Нарушение авторских прав преследуется по закону.', 'yareviews'); ?>
                </p>
            </div>
        </div>
    </div>
</div>
