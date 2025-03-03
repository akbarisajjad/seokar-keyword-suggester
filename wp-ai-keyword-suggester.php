<?php
/**
 * Plugin Name: keyword suggester
 * Plugin URI: https://seokar.click
 * Description: افزونه‌ای برای پیشنهاد کلمات کلیدی، عنوان و متا دیسکریپشن برای نوشته‌ها و محصولات وردپرس با استفاده از OpenAI.
 * Version: 1.0.0
 * Author: Sajjad Akbari
 * Author URI: https://sajjadakbari.ir
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: seokar-keyword-suggester
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// تعریف ثابت‌های افزونه
define('SEOKAR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SEOKAR_PLUGIN_URL', plugin_dir_url(__FILE__));

// بارگذاری فایل‌های موردنیاز
require_once SEOKAR_PLUGIN_DIR . 'includes/class-ai-suggester.php';
require_once SEOKAR_PLUGIN_DIR . 'includes/class-settings.php';

// بارگذاری فایل‌های زبان برای ترجمه
function seokar_load_textdomain() {
    load_plugin_textdomain('seokar-keyword-suggester', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'seokar_load_textdomain');

// فعال‌سازی افزونه
function seokar_activate() {
    add_option('seokar_api_key', '');
    add_option('seokar_auto_save', '0');
    add_option('seokar_language', 'auto');
}
register_activation_hook(__FILE__, 'seokar_activate');

// غیرفعال‌سازی افزونه
function seokar_deactivate() {
    // این قسمت را برای پاک کردن اطلاعات هنگام غیرفعال‌سازی خالی می‌گذاریم.
}
register_deactivation_hook(__FILE__, 'seokar_deactivate');

// بارگذاری اسکریپت‌ها و استایل‌ها در پنل مدیریت وردپرس
function seokar_admin_assets($hook) {
    if ($hook !== 'settings_page_seokar-keyword-suggester') {
        return;
    }

    wp_enqueue_style('seokar-admin-style', SEOKAR_PLUGIN_URL . 'assets/css/admin-style.css');
    wp_enqueue_script('seokar-admin-script', SEOKAR_PLUGIN_URL . 'assets/js/admin-script.js', ['jquery'], false, true);
}
add_action('admin_enqueue_scripts', 'seokar_admin_assets');
