jQuery(document).ready(function ($) {
    $('#zenwhsl_add_wishlist>a').click(function (e) {
        //$.post(document.location.protocol + '//' + document.location.host + '/wp-admin/admin-ajax.php', { postId: 1, action: 'zenwhsl_add_wishlist' }, function (response) {
        $.post(document.location.protocol + '//' + document.location.host + '/wp-admin/admin-ajax.php', myAjax, function (response) {
            //alert('Post added to wishlist');
            $('#zenwhsl_add_wishlist').text('Already wishlisted!');
            $('#zenwhsl_add_wishlist').addClass( 'already_wishlisted' );
        });
    });
});