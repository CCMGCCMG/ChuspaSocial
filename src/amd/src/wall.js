define(['jquery'], function($) {
    'use strict';

    return {
        
        focusFirstNewPost: function(container) {
            const $newPost = $(container).find('.post-item, article').first();
            
            if ($newPost.length) {
                if (!$newPost.is('a, button, input, [tabindex]')) {
                    $newPost.attr('tabindex', '-1');
                }
                $newPost.focus();
            }
        },

        init: function() {
            $(document).on('click', '[data-action="load-more"]', function() {
                $(document).one('wall:posts-loaded', function(e, data) {
                    if (data && data.content) {
                        this.focusFirstNewPost(data.content);
                    }
                }.bind(this));
            }.bind(this));
        }
    };
});