<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html__('Оставьте отзыв', 'yareviews'); ?></title>
    <?php 
    $form_settings = get_option('yareviews_settings', []);
    $bg_type = $form_settings['form_background'] ?? 'gradient';
    $bg_color1 = $form_settings['form_bg_color1'] ?? '#667eea';
    $bg_color2 = $form_settings['form_bg_color2'] ?? '#764ba2';
    $name_required = !empty($form_settings['form_name_required']);
    $email_required = !empty($form_settings['form_email_required']);
    $phone_required = !empty($form_settings['form_phone_required']);
    
    if ($bg_type === 'gradient') {
        $background_style = "linear-gradient(135deg, {$bg_color1} 0%, {$bg_color2} 100%)";
    } else {
        $background_style = $bg_color1;
    }
    ?>
    <?php wp_head(); ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: <?php echo esc_attr($background_style); ?>;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .yareviews-rating-form {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-header h1 {
            margin: 0 0 10px;
            font-size: 28px;
            color: #333;
        }
        
        .form-header p {
            margin: 0;
            color: #666;
            font-size: 16px;
        }
        
        .rating-step, .feedback-step {
            display: none;
        }
        
        .rating-step.active, .feedback-step.active {
            display: block;
        }
        
        .star-rating {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 30px 0;
        }
        
        .star-rating button {
            background: none;
            border: none;
            cursor: pointer;
            transition: transform 0.2s;
            padding: 0;
        }
        
        .star-rating button:hover {
            transform: scale(1.2);
        }
        
        .star-rating svg {
            width: 50px;
            height: 50px;
            fill: #ddd;
            transition: fill 0.2s;
        }
        
        .star-rating button:hover svg,
        .star-rating button.active svg {
            fill: #FFD700;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            font-family: inherit;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-actions {
            margin-top: 30px;
            text-align: center;
        }
        
        .btn {
            padding: 15px 40px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:link,
        .btn-primary:visited {
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        
        .success-message {
            display: none;
            text-align: center;
            padding: 40px;
        }
        
        .success-message.active {
            display: block;
        }
        
        .success-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        
        .optional-note {
            font-size: 14px;
            color: #999;
            margin-top: 5px;
        }
        
        .checkbox-group {
            margin-bottom: 20px;
        }
        
        .checkbox-group label {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            line-height: 1.5;
            color: #555;
        }
        
        .checkbox-group input[type="checkbox"] {
            margin-top: 3px;
            cursor: pointer;
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        
        .checkbox-group a {
            color: #667eea;
            text-decoration: none;
        }
        
        .checkbox-group a:hover {
            text-decoration: underline;
        }
        
        .required-field {
            color: #e74c3c;
            font-weight: bold;
        }
        
        @media (max-width: 640px) {
            body {
                padding: 10px;
            }
            
            .yareviews-rating-form {
                padding: 25px 20px;
                width: calc(100% - 20px);
                border-radius: 16px;
            }
            
            .form-header h1 {
                font-size: 22px;
            }
            
            .form-header p {
                font-size: 14px;
            }
            
            .star-rating {
                gap: 5px;
                margin: 20px 0;
            }
            
            .star-rating svg {
                width: 40px;
                height: 40px;
            }
            
            .form-group input,
            .form-group textarea {
                padding: 10px;
                font-size: 15px;
            }
            
            .form-group label {
                font-size: 14px;
            }
            
            .btn {
                width: 100%;
                padding: 12px 30px;
                font-size: 15px;
            }
            
            .checkbox-group label {
                font-size: 13px;
            }
            
            .success-icon {
                font-size: 48px;
            }
        }
    </style>
</head>
<body>
    <div class="yareviews-rating-form">
        <div class="form-header">
            <h1><?php echo esc_html__('Оцените наш сервис', 'yareviews'); ?></h1>
            <p><?php echo esc_html__('Ваше мнение очень важно для нас!', 'yareviews'); ?></p>
        </div>
        
        <div class="rating-step active">
            <p style="text-align: center; margin-bottom: 10px; color: #666;">
                <?php echo esc_html__('Как бы вы оценили нашу работу?', 'yareviews'); ?>
            </p>
            <div class="star-rating" id="star-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="button" data-rating="<?php echo $i; ?>">
                        <svg viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                        </svg>
                    </button>
                <?php endfor; ?>
            </div>
        </div>
        
        <div class="feedback-step">
            <form id="feedback-form">
                <div class="form-group">
                    <label for="name">
                        <?php echo esc_html__('Ваше имя', 'yareviews'); ?> 
                        <?php if ($name_required): ?>
                            <span class="required-field">*</span>
                        <?php else: ?>
                            <span class="optional-note">(<?php echo esc_html__('необязательно', 'yareviews'); ?>)</span>
                        <?php endif; ?>
                    </label>
                    <input type="text" id="name" name="name" placeholder="<?php echo esc_attr__('Иван Иванов', 'yareviews'); ?>" <?php echo $name_required ? 'required' : ''; ?>>
                </div>
                
                <div class="form-group">
                    <label for="email">
                        <?php echo esc_html__('Email', 'yareviews'); ?> 
                        <?php if ($email_required): ?>
                            <span class="required-field">*</span>
                        <?php else: ?>
                            <span class="optional-note">(<?php echo esc_html__('необязательно', 'yareviews'); ?>)</span>
                        <?php endif; ?>
                    </label>
                    <input type="email" id="email" name="email" placeholder="<?php echo esc_attr__('example@email.com', 'yareviews'); ?>" <?php echo $email_required ? 'required' : ''; ?>>
                </div>
                
                <div class="form-group">
                    <label for="phone">
                        <?php echo esc_html__('Телефон', 'yareviews'); ?> 
                        <?php if ($phone_required): ?>
                            <span class="required-field">*</span>
                        <?php else: ?>
                            <span class="optional-note">(<?php echo esc_html__('необязательно', 'yareviews'); ?>)</span>
                        <?php endif; ?>
                    </label>
                    <input type="tel" id="phone" name="phone" placeholder="<?php echo esc_attr__('+7 (999) 123-45-67', 'yareviews'); ?>" <?php echo $phone_required ? 'required' : ''; ?>>
                </div>
                
                <div class="form-group">
                    <label for="text"><?php echo esc_html__('Ваш отзыв', 'yareviews'); ?> <span class="required-field">*</span></label>
                    <textarea id="text" name="text" placeholder="<?php echo esc_attr__('Расскажите нам о вашем опыте...', 'yareviews'); ?>" required></textarea>
                </div>
                
                <?php 
                $settings = get_option('yareviews_settings', []);
                $privacy_url = $settings['privacy_policy_url'] ?? '';
                $offer_url = $settings['public_offer_url'] ?? '';
                ?>
                
                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" name="consent_data" id="consent_data" required>
                        <span>
                            <span class="required-field">*</span> <?php echo esc_html__('Даю своё согласие на обработку персональных данных в соответствии с', 'yareviews'); ?>
                            <?php if ($privacy_url): ?>
                                <a href="<?php echo esc_url($privacy_url); ?>" target="_blank"><?php echo esc_html__('Политикой конфиденциальности', 'yareviews'); ?></a>
                            <?php else: ?>
                                <?php echo esc_html__('Политикой конфиденциальности', 'yareviews'); ?>
                            <?php endif; ?>
                            <?php echo esc_html__('и принимаю условия', 'yareviews'); ?>
                            <?php if ($offer_url): ?>
                                <a href="<?php echo esc_url($offer_url); ?>" target="_blank"><?php echo esc_html__('Публичной оферты', 'yareviews'); ?></a>
                            <?php else: ?>
                                <?php echo esc_html__('Публичной оферты', 'yareviews'); ?>
                            <?php endif; ?>.
                        </span>
                    </label>
                </div>
                
                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" name="consent_newsletter" id="consent_newsletter">
                        <span>
                            <?php echo esc_html__('Я согласен(а) получать информационные рассылки и уведомления об акциях на указанный email. Подтверждаю, что могу отменить подписку в любое время.', 'yareviews'); ?>
                        </span>
                    </label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo esc_html__('Отправить', 'yareviews'); ?>
                    </button>
                </div>
            </form>
        </div>
        
        <div class="success-message">
            <div class="success-icon">✅</div>
            <h2><?php echo esc_html__('Спасибо за ваш отзыв!', 'yareviews'); ?></h2>
            <p id="success-text"><?php echo esc_html__('Ваше мнение очень важно для нас!', 'yareviews'); ?></p>
            <div class="form-actions" style="margin-top: 20px;">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <?php echo esc_html__('На главную', 'yareviews'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <script>
    let selectedRating = 0;
    
    document.querySelectorAll('#star-rating button').forEach((btn, index) => {
        btn.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.rating);
            
            document.querySelectorAll('#star-rating button').forEach((b, i) => {
                if (i < selectedRating) {
                    b.classList.add('active');
                } else {
                    b.classList.remove('active');
                }
            });
            
            setTimeout(() => {
                document.querySelector('.rating-step').classList.remove('active');
                document.querySelector('.feedback-step').classList.add('active');
            }, 500);
        });
    });
    
    document.getElementById('feedback-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            rating: selectedRating,
            name: formData.get('name'),
            email: formData.get('email'),
            phone: formData.get('phone'),
            text: formData.get('text'),
            consent_data: formData.get('consent_data') === 'on',
            consent_newsletter: formData.get('consent_newsletter') === 'on'
        };
        
        if (!data.consent_data) {
            alert('<?php echo esc_js(__('Пожалуйста, дайте согласие на обработку персональных данных', 'yareviews')); ?>');
            return;
        }
        
        try {
            const response = await fetch('<?php echo esc_url(rest_url('yareviews/v1/submit-rating')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest'); ?>'
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                document.querySelector('.feedback-step').classList.remove('active');
                document.querySelector('.success-message').classList.add('active');
            }
        } catch (error) {
            alert('<?php echo esc_js(__('Произошла ошибка. Попробуйте снова.', 'yareviews')); ?>');
        }
    });
    </script>
    <?php wp_footer(); ?>
</body>
</html>
