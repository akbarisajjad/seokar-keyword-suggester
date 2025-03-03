<?php

if (!defined('ABSPATH')) {
    exit;
}

class AI_Suggester {
    // افزودن متاباکس به نوشته‌ها و محصولات ووکامرس
    public static function add_keyword_meta_box() {
        add_meta_box(
            'seokar_keyword_suggester',
            __('AI Suggestions', 'seokar-keyword-suggester'),
            [__CLASS__, 'render_meta_box'],
            ['post', 'product', 'page'],
            'side',
            'high'
        );
    }

    // نمایش متاباکس در ویرایشگر وردپرس
    public static function render_meta_box($post) {
        wp_nonce_field('seokar_suggester_nonce_action', 'seokar_suggester_nonce');

        // بازیابی پیشنهادات ذخیره‌شده در متادیتای نوشته
        $saved_suggestions = get_post_meta($post->ID, '_seokar_suggestions', true);
        ?>
        <div id="ai-suggestions">
            <button type="button" id="get-suggestions" class="button button-primary"><?php _e('Get AI Suggestions', 'seokar-keyword-suggester'); ?></button>
            <div id="suggestions-results">
                <?php if ($saved_suggestions): ?>
                    <strong><?php _e('Saved Suggestions:', 'seokar-keyword-suggester'); ?></strong>
                    <ul>
                        <?php foreach ($saved_suggestions as $suggestion): ?>
                            <li class="suggestion-item"><?php echo esc_html($suggestion); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><?php _e('No suggestions available.', 'seokar-keyword-suggester'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

// ثبت متاباکس در ویرایشگر وردپرس
add_action('add_meta_boxes', ['AI_Suggester', 'add_keyword_meta_box']);

// دریافت پیشنهادات از OpenAI و ذخیره در متادیتای نوشته
add_action('wp_ajax_get_ai_suggestions', 'seokar_fetch_suggestions');

function seokar_fetch_suggestions() {
    // بررسی دسترسی کاربر
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(__('You do not have permission to perform this action.', 'seokar-keyword-suggester'));
    }

    // بررسی nonce برای امنیت بیشتر
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'seokar_suggester_nonce_action')) {
        wp_send_json_error(__('Invalid request.', 'seokar-keyword-suggester'));
    }

    // دریافت API Key و تنظیمات افزونه
    $api_key = get_option('seokar_api_key');
    $auto_save = get_option('seokar_auto_save', '0');
    $language = get_option('seokar_language', 'auto');
    $post_id = intval($_POST['post_id']);

    if (!$api_key) {
        wp_send_json_error(__('API Key is not set.', 'seokar-keyword-suggester'));
    }

    // بررسی پیشنهادات قبلی در متادیتای نوشته
    $existing_suggestions = get_post_meta($post_id, '_seokar_suggestions', true);
    if ($existing_suggestions) {
        wp_send_json_success($existing_suggestions);
    }

    $content = sanitize_text_field($_POST['content']);
    $language = ($language === 'auto') ? detect_language($content) : $language;

    $prompt = "لطفاً ۵ کلمه کلیدی، یک عنوان سئو و یک توضیحات متا برای متن زیر به زبان {$language} پیشنهاد بده:\n\n{$content}";

    // ارسال درخواست به OpenAI
    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'model' => 'gpt-4',
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'max_tokens' => 150,
        ]),
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(__('Error connecting to OpenAI API.', 'seokar-keyword-suggester'));
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);
    if (!isset($body['choices'][0]['message']['content'])) {
        wp_send_json_error(__('Invalid response from AI.', 'seokar-keyword-suggester'));
    }

    $suggestions = explode("\n", trim($body['choices'][0]['message']['content']));

    // ذخیره پیشنهادات در متادیتای نوشته
    if ($auto_save === '1') {
        update_post_meta($post_id, '_seokar_suggestions', $suggestions);
    }

    wp_send_json_success($suggestions);
}

// تابع تشخیص زبان محتوا
function detect_language($text) {
    if (preg_match('/[اآبپتثجچحخدذرزژسشصضطظعغفقکگلمنوهی]/u', $text)) {
        return 'فارسی';
    } elseif (preg_match('/[a-zA-Z]/', $text)) {
        return 'English';
    } elseif (preg_match('/[а-яА-Я]/u', $text)) {
        return 'Русский';
    } else {
        return 'Unknown';
    }
}
