<?php

if (!defined('ABSPATH')) {
    exit;
}

class AI_Suggester_Settings {
    // ثبت صفحه تنظیمات در منوی وردپرس
    public static function register_settings_page() {
        add_options_page(
            __('Seokar Keyword Suggester Settings', 'seokar-keyword-suggester'),
            __('سئوکار پیشنهاد کلمه کلیدی', 'seokar-keyword-suggester'),
            'manage_options',
            'seokar-keyword-suggester',
            [__CLASS__, 'settings_page']
        );
    }

    // نمایش صفحه تنظیمات
    public static function settings_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'seokar-keyword-suggester'));
        }

        // ذخیره تنظیمات
        if (isset($_POST['seokar_save_settings'])) {
            check_admin_referer('seokar_settings_nonce');

            update_option('seokar_api_key', sanitize_text_field($_POST['seokar_api_key']));
            update_option('seokar_auto_save', isset($_POST['seokar_auto_save']) ? '1' : '0');
            update_option('seokar_language', sanitize_text_field($_POST['seokar_language']));

            echo '<div class="updated"><p>' . __('Settings saved successfully!', 'seokar-keyword-suggester') . '</p></div>';
        }

        $api_key = get_option('seokar_api_key', '');
        $auto_save = get_option('seokar_auto_save', '0');
        $language = get_option('seokar_language', 'auto');
        ?>

        <div class="wrap">
            <h1><?php _e('Seokar Keyword Suggester Settings', 'seokar-keyword-suggester'); ?></h1>
            <form method="post">
                <?php wp_nonce_field('seokar_settings_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th><label for="seokar_api_key"><?php _e('Enter your OpenAI API Key', 'seokar-keyword-suggester'); ?></label></th>
                        <td><input type="text" name="seokar_api_key" id="seokar_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" required></td>
                    </tr>
                    <tr>
                        <th><label for="seokar_auto_save"><?php _e('Automatically Save Keywords to Metadata', 'seokar-keyword-suggester'); ?></label></th>
                        <td><input type="checkbox" name="seokar_auto_save" id="seokar_auto_save" value="1" <?php checked('1', $auto_save); ?>></td>
                    </tr>
                    <tr>
                        <th><label for="seokar_language"><?php _e('Select Language for Suggestions', 'seokar-keyword-suggester'); ?></label></th>
                        <td>
                            <select name="seokar_language" id="seokar_language">
                                <option value="auto" <?php selected($language, 'auto'); ?>><?php _e('Detect Automatically', 'seokar-keyword-suggester'); ?></option>
                                <option value="fa" <?php selected($language, 'fa'); ?>><?php _e('Persian (Farsi)', 'seokar-keyword-suggester'); ?></option>
                                <option value="en" <?php selected($language, 'en'); ?>><?php _e('English', 'seokar-keyword-suggester'); ?></option>
                                <option value="ar" <?php selected($language, 'ar'); ?>><?php _e('Arabic', 'seokar-keyword-suggester'); ?></option>
                                <option value="de" <?php selected($language, 'de'); ?>><?php _e('German', 'seokar-keyword-suggester'); ?></option>
                                <option value="fr" <?php selected($language, 'fr'); ?>><?php _e('French', 'seokar-keyword-suggester'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="seokar_save_settings" class="button button-primary" value="<?php _e('Save Settings', 'seokar-keyword-suggester'); ?>">
                </p>
            </form>
        </div>

        <?php
    }
}

// اضافه کردن صفحه تنظیمات به منوی مدیریت وردپرس
add_action('admin_menu', ['AI_Suggester_Settings', 'register_settings_page']);
