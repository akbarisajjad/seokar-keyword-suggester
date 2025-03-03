<?php
if (!defined('ABSPATH')) {
    exit;
}

// دریافت داده‌های ذخیره‌شده
$saved_suggestions = get_post_meta(get_the_ID(), '_seokar_suggestions', true);
?>

<div id="ai-suggestions">
    <button type="button" id="get-suggestions" class="button button-primary">
        <?php _e('Get AI Suggestions', 'seokar-keyword-suggester'); ?>
    </button>
    
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
