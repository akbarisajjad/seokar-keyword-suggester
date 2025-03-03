<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// حذف گزینه‌های ذخیره‌شده در دیتابیس
delete_option('seokar_api_key');
delete_option('seokar_auto_save');
delete_option('seokar_language');

// حذف متادیتاهای ذخیره‌شده در نوشته‌ها
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_seokar_suggestions'");
