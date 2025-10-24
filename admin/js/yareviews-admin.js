(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        $('.yareviews-color-picker').wpColorPicker();
        
        $('.yareviews-range-slider').on('input', function() {
            $(this).next('.range-value').text($(this).val());
        });
        
        $('.yareviews-tab-button').on('click', function() {
            const tab = $(this).data('tab');
            
            $('.yareviews-tab-button').removeClass('active');
            $(this).addClass('active');
            
            $('.yareviews-tab-content').removeClass('active');
            $('#' + tab + '-tab').addClass('active');
        });
        
        $('#yareviews-toggle-add-form').on('click', function() {
            $('#yareviews-add-review-form').slideToggle();
        });
        
        $('#yareviews-cancel-add').on('click', function() {
            $('#yareviews-add-review-form').slideUp();
        });
        
        var avatarMediaUploader;
        
        $('.yareviews-upload-avatar-btn').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var inputField = button.siblings('#author_avatar');
            var previewContainer = button.siblings('.yareviews-avatar-preview');
            var removeBtn = button.siblings('.yareviews-remove-avatar-btn');
            
            if (avatarMediaUploader) {
                avatarMediaUploader.open();
                return;
            }
            
            avatarMediaUploader = wp.media({
                title: yareviews_admin.strings.select_image,
                button: {
                    text: yareviews_admin.strings.use_image
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });
            
            avatarMediaUploader.on('select', function() {
                var attachment = avatarMediaUploader.state().get('selection').first().toJSON();
                
                inputField.val(attachment.url);
                
                previewContainer.find('img').attr('src', attachment.url);
                previewContainer.show();
                removeBtn.show();
            });
            
            avatarMediaUploader.open();
        });
        
        $('.yareviews-remove-avatar-btn').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var inputField = button.siblings('#author_avatar');
            var previewContainer = button.siblings('.yareviews-avatar-preview');
            
            inputField.val('');
            previewContainer.hide();
            button.hide();
        });
        
        if ($('#author_avatar').val()) {
            var avatarUrl = $('#author_avatar').val();
            $('.yareviews-avatar-preview').find('img').attr('src', avatarUrl);
            $('.yareviews-avatar-preview').show();
            $('.yareviews-remove-avatar-btn').show();
        }
        
    });
    
})(jQuery);
