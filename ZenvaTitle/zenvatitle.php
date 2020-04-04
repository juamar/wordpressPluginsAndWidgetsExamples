<?php
/*
*   Plugin Name: Zenva Happy Titles
*   Plugin URI: http://tecandweb.net
*   Description: Wordpress Plugin that modifies the title, the content, and the list of categories for a post.
*   Version: 1.0
*   Author: Juan Ignacio Avendaño Huergo
*   Author URI: http://tecandweb.net
*   License: GPL2
*/

add_filter('the_title', 'zenvatitle_title');
add_filter('the_content', 'zenvatitle_content');
add_filter('list_cats','zenvatitle_cats');

/**
* modify title
*/
function zenvatitle_title($text)
{
    return ';D '.$text;
}

/**
* modify content
*/
function zenvatitle_content($text)
{
    return strtoupper($text);
}

/**
* modify categories
*/
function zenvatitle_cats($text)
{
    return strtolower($text);
}

?>
