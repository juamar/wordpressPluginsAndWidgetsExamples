<?php
/*
*   Plugin Name: ZenvaWishlist plugin
*   Plugin URI: http://tecandweb.net
*   Description: Add posts to a wishlist.
*   Version: 1.0
*   Author: Juan Ignacio Avendaño Huergo
*   Author URI: http://tecandweb.net
*   License: GPL2
*/

require_once(__DIR__.'/widget/ZenvaWishlistWidget.php');

add_action('admin_menu', 'zenwhsl_plugin_menu');
add_action('admin_init', 'zenwhsl_admin_init');

add_action('widgets_init', 'zenwhsl_widget_wishlist_init');
add_action('wp_enqueue_scripts', 'zenwhsl_init');

add_action('wp_ajax_zenwhsl_add_wishlist','zenwhsl_add_wishlist_process');
//utilice wp_ajax_nopriv_ para hacer llamadas sin estar autenticado en WP. No funcionará si estas logueado, solo cuando no. Así como el anterior funciona solo cuando estás logueado.
//add_action('wp_ajax_nopriv_zenwhsl_add_wishlist','zenwhsl_add_wishlist_process');

add_action('wp_dashboard_setup', 'zenwhsl_create_dashboard_widget');

$zenwhslwidget;

function zenwhsl_plugin_menu()
{
    add_options_page('Zenva wishlist options', 'Zenva wishlist', 'manage_options', 'zenwhsl', 'zenwhsl_plugin_options');
}

function zenwhsl_admin_init()
{
    register_setting('zenwhsl-group', 'zenwhsl_dashboard_title');
    register_setting('zenwhsl-group', 'zenwhsl_number_of_items');
}

function zenwhsl_plugin_options()
{
    ?>

    <div class="wrap">

        <?php screen_icon(); ?>

        <h2>Zenva wishlist</h2>
        <form action="options.php" method="post">

            <?php settings_fields('zenwhsl-group'); ?>
            <?php (@do_settings_fields('zenwhsl-group')); 

            $dashboard_title = esc_attr(get_option('zenwhsl_dashboard_title'));
            $number_of_items = (int) get_option('zenwhsl_number_of_items');
              
            ?>

            <table class="form-table">
                <tr valing="top">
                    <th scope="row"><label for="zenwhsl_dashboard_title">Dashboard widget title</label></th>
                    <td>
                        <input type="text" id="zenwhsl_dashboard_title" name="zenwhsl_dashboard_title" value="<?php echo $dashboard_title; ?>" />
                        <br><small>Add help text</small>
                    </td>
                </tr>
                <tr valing="top">
                    <th scope="row"><label for="zenwhsl_number_of_items">Number of items</label></th>
                    <td>
                        <input type="text" id="zenwhsl_number_of_items" name="zenwhsl_number_of_items" value="<?php echo $number_of_items; ?>" />
                        <br><small>Add help text</small>
                        
                    </td>
                </tr>
            </table>
            <?php /* No funciona el guardado!! */ @submit_button(); ?>

        </form>
    </div>

    <?php
}

function zenwhsl_widget_wishlist_init()
{
    global $zenwhslwidget ;

    $zenwhslwidget = new ZenvaWishlistWidget();
    register_widget('ZenvaWishlistWidget');
}

function zenwhsl_init()
{
    wp_register_script('zenvawishlist-js', plugins_url('/js/zenvawishlist.js', __FILE__), array('jquery'));

    wp_enqueue_script('jquery');
    wp_enqueue_script('zenvawishlist-js');

    /**
    *   I don't like this really. We are defining a variable that can be used in JSs files.
    */
    global $post;
    wp_localize_script('zenvawishlist-js', 'myAjax', array(
        'postId' => get_the_ID(),
        'action' => 'zenwhsl_add_wishlist'
    ));
}

function zenwhsl_add_wishlist_process()
{
    //echo 'El articulo tiene id '.$_POST['postId'];
    global $zenwhslwidget ;

    $post_id = (int) $_POST['postId'];

    $user = wp_get_current_user();

    if (!$zenwhslwidget -> zenwhsl_has_wishlisted($post_id, $user))
    {
        add_user_meta($user->ID, 'zenwhsl_wanted_posts', $post_id);
    }

    exit();
}

function zenwhsl_create_dashboard_widget()
{
    $dashboard_title = esc_attr( get_option('zenwhsl_dashboard_title') ? get_option('zenwhsl_dashboard_title') : 'Default title'  );

    wp_add_dashboard_widget('zenwhsl_dashboard', $dashboard_title, 'zenwhsl_show_dashboard_widget');
}

function zenwhsl_show_dashboard_widget()
{
    $user = wp_get_current_user();
    $values = get_user_meta( $user->ID, 'zenwhsl_wanted_posts');
    $number_of_items = (int) get_option('zenwhsl_number_of_items') ? get_option('zenwhsl_number_of_items') : 4;

    echo '<ul>';
    for ($i = 0 ; $i < sizeof($values) && $i < $number_of_items ; $i++)
    {
        $currentPost = get_post($values[$i]);

        echo '<li>' . $currentPost -> post_title . '</li>';
    }
    echo '</ul>';
}

?>