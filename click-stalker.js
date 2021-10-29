/*
Uses jQuery to dynamically convert all A tags on a page that go to items in the uploads to redirect via the click tracker. It  should work with any method of putting assets on the page.

jqueryDefer() allows you to wait for jQuery to load before running the function. This means it is a early as possible.
*/

function jqueryDefer(method) {
    if (window.jQuery) {
        method(window);
    } else {
        setTimeout(function() { jqueryDefer(method) }, 50);
    }
}

jqueryDefer(
    function() {
        jQuery(document).ready( function() {
            jQuery('a').each(function() {
                var uri = jQuery(this).attr('href'), prefix = '/wp-content/uploads', click_prefix = '/click';
                
                if( uri.startsWith(prefix) ) {
                    jQuery(this).attr('href', click_prefix + uri.substring(prefix.length) + '/', '_blank');
                }
            });
        });
    }
);
