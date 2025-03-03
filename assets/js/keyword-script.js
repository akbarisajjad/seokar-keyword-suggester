jQuery(document).ready(function($) {
    $('#get-suggestions').click(function() {
        var postId = $('#post_ID').val();
        var content = $('#content').val() || $('#excerpt').val();

        if (!content) {
            alert("لطفاً محتوای نوشته را وارد کنید.");
            return;
        }

        $('#suggestions-results').html("<p>در حال دریافت پیشنهادات...</p>");

        $.ajax({
            url: seokar_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_ai_suggestions',
                content: content,
                post_id: postId,
                nonce: $('#seokar_suggester_nonce').val()
            },
            success: function(response) {
                if (response.success) {
                    var suggestions = response.data;
                    var output = "<ul>";
                    suggestions.forEach(function(suggestion) {
                        output += "<li class='suggestion-item'>" + suggestion + "</li>";
                    });
                    output += "</ul>";
                    $('#suggestions-results').html(output);
                } else {
                    $('#suggestions-results').html("<p style='color:red;'>" + response.data + "</p>");
                }
            },
            error: function() {
                $('#suggestions-results').html("<p style='color:red;'>خطایی رخ داد. لطفاً دوباره امتحان کنید.</p>");
            }
        });
    });
});
