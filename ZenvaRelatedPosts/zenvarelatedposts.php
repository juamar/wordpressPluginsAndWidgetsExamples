<?php
/*
*   Plugin Name: Zenva Related Posts
*   Plugin URI: http://tecandweb.net
*   Description: Plugin that adds links to related posts at the end of the content.
*   Version: 1.0
*   Author: Juan Ignacio Avendaño Huergo
*   Author URI: http://tecandweb.net
*   License: GPL2
*/

add_filter('the_content', 'zenvarp_add_related_posts');

/**
* Add links to related posts at the end of the content
*/
function zenvarp_add_related_posts($content)
{
    if(!is_singular('post'))
    {
        return $content;
    }

    $categories = get_the_terms(get_the_ID(), 'category');
    $categoriesIds = array();

    foreach($categories as $category)
    {
        $categoriesIds[] = $category->term_id;
    }

    $loop = new WP_Query(array(
        'category_in' => $categoriesIds,
        'posts_per_page' => 4,
        'post__not_in' => array(get_the_ID()),
        'orderby' => 'rand'
    ));

    if($loop->have_posts())
    {
        $content .= 'Related Posts:<br><ul>';
        while($loop -> have_posts())
        {
            $loop->the_post();
            $content .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
        }
        $content .= '</ul>';
    }

    wp_reset_query();

    return $content;
}

?>
