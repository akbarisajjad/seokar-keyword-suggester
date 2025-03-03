jQuery(document).ready(function($) {
    // نمایش پیام بعد از ذخیره تنظیمات
    if (window.location.href.includes("&status=success")) {
        $('.wrap h1').after('<div class="updated notice"><p>تنظیمات با موفقیت ذخیره شد!</p></div>');
    }

    // تأیید تغییر API Key
    $('#seokar_api_key').on('change', function() {
        var confirmChange = confirm("آیا مطمئن هستید که می‌خواهید کلید API را تغییر دهید؟ تنظیمات شما ممکن است تحت تأثیر قرار بگیرند.");
        if (!confirmChange) {
            $(this).val($(this).data('original-value'));
        } else {
            $(this).data('original-value', $(this).val());
        }
    });

    // تأیید تغییر زبان
    $('#seokar_language').on('change', function() {
        alert("توجه: تغییر زبان ممکن است بر پیشنهادات دریافتی تأثیر بگذارد.");
    });
});
