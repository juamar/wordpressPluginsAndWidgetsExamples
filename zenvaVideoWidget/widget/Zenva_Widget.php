<?php

class Zenva_Widget extends WP_Widget
{
    /**
    *   Constructor
    */
    function Zenva_Widget()
    {
        $widget_options = array(
            'classname' => 'zenva_class', //for css
            'description' => 'Show a Youtube Video from post metadata'
        );
        $this->__construct('zenva_id', 'YouTube Video', $widget_options);
    }

    /**
    *   Show Widget form in appereance - Widgets
    */
    function form($instance)
    {
        $defaults = array('title' => 'YouTube Video');
        $instance = wp_parse_args((array) $instance, $defaults);

        $title = esc_attr($instance['title']);

        echo '<p>Title: <input class="widefat" name="'.$this->get_field_name('title').'" type="text" value="'.$title.'" /></p>';
    }

    /**
    *   Save widget form
    */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;       
    }

    /**
    *   Show Widget on website
    */
    function widget($args, $instance)
    {
        extract ($args);
        $title = apply_filters('widget_title', $instance['title']);

        //show if single post
        if (is_single())
        {
            echo $before_widget;
            echo $before_title.$title.$after_title;

            //get post metadata
            $zenva_youtube = esc_url(get_post_meta(get_the_ID(), 'zenva_youtube_link', true));

            //embed video
            echo '<iframe height=200 src="http://youtube.com/embed/'.$this->get_videoid($zenva_youtube).'" frameborder=0 allowfullscreen></iframe>';

            $after_widget;
        }
    }

    function get_videoid($url)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
        return $my_array_of_vars['v'];
    }
}

?>
