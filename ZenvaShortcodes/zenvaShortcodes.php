<?php
/*
*   Plugin Name: Zenva Shortcodes
*   Plugin URI: http://tecandweb.net
*   Description: Plugin that enables a shortcode for money units conversor.
*   Version: 1.0
*   Author: Juan Ignacio Avendaño Huergo
*   Author URI: http://tecandweb.net
*   License: GPL2
*/

add_action('init', 'zenva_register_shortcodes');

function zenva_register_shortcodes()
{
    //register shortcode [rate from="USD" to="EUR"]USD/EUR[/rate]
    add_shortcode('rate', 'zenva_rate');
}

function zenva_rate($args, $content)
{
    //return strtoupper($content).' - '.$args['parametro'];
    $result = wp_remote_get('http://finance.yahoo.com/d/quotes.csv?s='.$args['from'].$args['to'].'=X&f=l1');

    return $result['body'].' '.esc_attr($content);
}

?>