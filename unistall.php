<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// حذف گزینه‌های ذخیره‌شده در دیتابیس
delete_option('seokar_api_key');
delete_option('seokar_auto_save');
delete_option('seokar_language');
