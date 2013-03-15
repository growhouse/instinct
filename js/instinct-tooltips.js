 jQuery(document).ready( function($) {
                //jQuery selector to point to 
                jQuery('#wp-admin-bar-instinct-edit-mode a').pointer({
                    content: '<h3>Easy editing with Instinct </h3> <p>Simply click this button to enable in-page editing.</p><p>You can see what is editable by hovering over areas of the page. Click to edit, then save or cancel the changes. You can exit edit mode by clicking this button again.</p>',
                    position: 'top',
                    close: function() {
                        // This function is fired when you click the close button
                    }
                }).pointer('open');
            });