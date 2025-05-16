jQuery(document).ready(function($) {
  console.log("enqueue working")

  // Make the quicklinks list sortable
  $('#user-quicklinks-list').sortable({
    // axis: 'y', // Uncomment to allow vertical dragging only
    handle: '.handle', // Make the link the draggable handle
    update: function(event, ui) {
        var newOrder = $(this).sortable('toArray', { attribute: 'data-index' });
        var nonce = userQuicklinksAjax.reorder_nonce;

        $.ajax({
            url: userQuicklinksAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'reorder_user_quicklinks',
                order: newOrder,
                _ajax_nonce: nonce
            },
            success: function(response) {
                if (!response.success) {
                    alert(response.data || userQuicklinksAjax.i18n.generic_error);
                } else {
                    // Re-index the data-index attributes after successful reorder
                    $('#user-quicklinks-list li').each(function(index) {
                        $(this).attr('data-index', index);
                        $(this).find('.delete-quicklink').data('index', index); // Update delete button index too
                    });
                }
            },
            error: function() {
                alert(userQuicklinksAjax.i18n.generic_error);
            }
        });
    }
});

  $('#add-quicklink-button').on('click', function(e) {
      e.preventDefault();

      console.log("form submit")

      var title = $('#quicklink-title').val();
      var url = $('#quicklink-url').val();
      var nonce = $('#add_quicklink_nonce').val();

      if (!title || !url) {
          alert('Please fill in both title and URL.');
          return;
      }

      $.ajax({
          url: userQuicklinksAjax.ajaxurl,
          type: 'POST',
          data: {
              action: 'add_user_quicklink',
              title: title,
              url: url,
              _ajax_nonce: userQuicklinksAjax.add_nonce
          },
          success: function(response) {
              if (response.success) {
                  $('#user-quicklinks-list').html(response.data);
                  $('#add-quicklink-form')[0].reset(); // Clear the form
                  // Re-initialize sortable after deleting an item
                  $('#user-quicklinks-list').sortable('refresh');
              } else {
                  alert(response.data || userQuicklinksAjax.i18n.generic_error);
              }
          },
          error: function() {
              alert(userQuicklinksAjax.i18n.generic_error);
          }
      });
  });

  $(document).on('click', '.delete-quicklink', function() {
      if (confirm(userQuicklinksAjax.i18n.delete_confirm)) {
          var index = $(this).data('index');
          var nonce = userQuicklinksAjax.delete_nonce;

          $.ajax({
              url: userQuicklinksAjax.ajaxurl,
              type: 'POST',
              data: {
                  action: 'delete_user_quicklink',
                  index: index,
                  _ajax_nonce: nonce
              },
              success: function(response) {
                  if (response.success) {
                      $('#user-quicklinks-list').html(response.data);
                      // Re-initialize sortable after deleting an item
                      $('#user-quicklinks-list').sortable('refresh');
                  } else {
                      alert(response.data || userQuicklinksAjax.i18n.generic_error);
                  }
              },
              error: function() {
                  alert(userQuicklinksAjax.i18n.generic_error);
              }
          });
      }
  });
});
