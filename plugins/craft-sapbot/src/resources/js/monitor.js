$(function() {

    $('.unmatched-query a.source').on('click', function(e) {

        e.preventDefault();

        const $this = $(this);
        const id = $this.parent().parent().data('id');
        const $modal = $('<div class="modal monitor-modal"/>');
        const $spinner = $('<span class="spinner big"/>').appendTo($modal);
        const $modalBody = $('<div class="body"/>').appendTo($modal);

        new Garnish.Modal($modal, {
            resizable: true
        });

        // Fetch the whole conversation for the unmatched query and display it in a modal window.
        Craft.postActionRequest('sapbot/monitor/conversation', { entryId: id }, function(response, textStatus) {
            if (textStatus === 'success' && response.html) {
                $modalBody.html(response.html);
                $spinner.remove();
            }
        });
    });

});
