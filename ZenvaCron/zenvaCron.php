<?php
/*
*   Plugin Name: Zenva Cron
*   Plugin URI: http://tecandweb.net
*   Description: Plugin that send annoying emails every hour.
*   Version: 1.0
*   Author: Juan Ignacio Avendaño Huergo
*   Author URI: http://tecandweb.net
*   License: GPL2
*/

add_action('init', 'zencr_init_cronJob');
add_action('zencr_send_email_hook', 'zencr_send_email');

function zencr_init_cronJob()
{
    if (!wp_next_scheduled('zencr_send_email_hook'))
    {
        wp_schedule_event(time(), 'hourly', 'zencr_send_email_hook');
    }
}

function zencr_send_email()
{
    $admin_email = get_bloginfo('admin_email');

    wp_mail($admin_email, 'admin', 'soy un correo que te va a tocas las p... cada hora');
}

?>