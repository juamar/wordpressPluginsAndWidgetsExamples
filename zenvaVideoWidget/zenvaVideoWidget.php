<?php
/*
*   Plugin Name: Zenva Video Widget
*   Plugin URI: http://tecandweb.net
*   Description: Insert your youtube video link to your posts, by using our amazing widget!
*   Version: 1.0
*   Author: Juan Ignacio Avendaño Huergo
*   Author URI: http://tecandweb.net
*   License: GPL2
*/

require_once(__DIR__.'/widget/Zenva_Widget.php');

add_action('add_meta_boxes', 'zenva_add_metabox');
add_action('save_post', 'zenva_save_metabox');
add_action('init', 'zenva_register_shortcodes2');
add_action('widgets_init', 'zenva_widget_init');


function zenva_add_metabox()
{
    //adding the box;
    add_meta_box('zenva_youtube', 'YouTube Video Link', 'zenva_youtube_handler', 'post');
}

//Filling the box
function zenva_youtube_handler()
{
     //if no meta, then create meta
     $values = get_post_meta( get_the_ID() );
     if (!isset($values['zenva_youtube_link']))
     {
        add_post_meta(get_the_ID(), 'zenva_youtube_link','');
     }
    
    wp_reset_postdata();
    $values = get_post_meta( get_the_ID() );
    $zenva_link = esc_attr($values['zenva_youtube_link'][0]);
    echo '<label for="zenva_youtube">Youtube Video Link</label><input type="text" id="zenva_youtube_link" name="zenva_youtube_link" value="'.$zenva_link.'" />';
}

function zenva_save_metabox($post_id)
{
    if (!wp_is_post_autosave( $post_id ) &&
         current_user_can('edit_post') &&
         isset($_POST['zenva_youtube_link']))
    {
        update_post_meta($post_id, 'zenva_youtube_link', esc_url($_POST['zenva_youtube_link']));
        wp_reset_postdata();
    }
}

function zenva_register_shortcodes2()
{
    //register shortcode [rate from="USD" to="EUR"]USD/EUR[/rate]
    add_shortcode('zenva_youtube', 'zenva_link');
}

function zenva_link($args, $content)
{
    $values = get_post_meta( get_the_ID() );
    $zenva_link = esc_attr($values['zenva_youtube_link'][0]);
    $result = '<a href="'.$zenva_link.'" target="_blank" >Enlace a vídeo</a>';
    return $result;
}

/**
*   Initialize widget
*/
function zenva_widget_init()
{
    register_widget('Zenva_Widget');
}

?>