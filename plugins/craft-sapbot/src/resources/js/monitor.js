(function($) {

    Craft.SapMonitor = Garnish.Base.extend({

        init: function() {
            this.setupAdminTable();
        },

        setupAdminTable: function() {
            new Craft.AdminTable({
                tableSelector: '#monitor',
                noObjectsSelector: '#noqueries',
                sortable: true,
                deleteAction: 'sapbot/monitor/delete',
                confirmDeleteMessage: 'Are you sure you want to delete this entry?'
                //newObjectBtnSelector: '#newfeedcontainer',
                //reorderAction: 'feed-me/feeds/reorder-feeds',
            });

            this.addListener($('.unmatched-query a.source'), 'click', function(e) {
                e.preventDefault();
                const id = $(event.target).closest('tr').data('id');
                this.showConversationDialog(id);
            });

        },

        showConversationDialog: function(id) {
            // Construct modal dialog.
            const $modal = $('<form class="modal monitor-modal"/>').appendTo(Garnish.$bod),
                  $header = $('<header class="header"><h2>Conversation</h2></header>').appendTo($modal),
                  $body = $('<div class="body"/>').appendTo($modal),
                  $spinner = $('<span class="spinner big"/>').appendTo($modal),
                  $footer = $('<footer class="footer"/>').appendTo($modal),
                  $buttons = $('<div class="buttons right"/>').appendTo($footer),
                  $cancelBtn = $('<div class="btn">' + Craft.t('app', 'Close') + '</div>').appendTo($buttons);

            Craft.initUiElements($body);

            const modal = new Garnish.Modal($modal, {
                hideOnEsc: false,
                hideOnShadeClick: false
            });

            this.addListener($cancelBtn, 'click', function() {
                modal.hide();
            });

            // Fetch the whole conversation for the unmatched query and display it in a modal window.
            Craft.postActionRequest('sapbot/monitor/conversation', { entryId: id }, function(response, textStatus) {
                if (textStatus === 'success' && response.html) {
                    $body.addClass('ready').html(response.html);
                    $spinner.remove();
                }
            });
        }
    });

})(jQuery);
