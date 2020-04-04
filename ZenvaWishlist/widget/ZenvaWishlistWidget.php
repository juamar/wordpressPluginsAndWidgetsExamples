<?php

class ZenvaWishlistWidget extends WP_Widget
{
    /**
    *   Constructor
    */
    function ZenvaWishlistWidget()
    {
        $widget_options = array(
            'classname' => 'zenva_Wishlist_class', //for css
            'description' => 'Add items to wishlist'
        );
        $this->__construct('zenva_wishlist_id', 'Wishlist', $widget_options);
    }

    /**
    *   Show Widget form in appereance - Widgets
    */
    function form($instance)
    {
        $defaults = array('title' => 'Wishlist');
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

            $user = wp_get_current_user();
            $post_id = get_the_ID();

            //echo $post_id . ' ' . $user -> ID;
            //echo $this -> zenwhsl_has_wishlisted($post_id, $user);

            if (!is_user_logged_in())
            {
                echo '<span id="zenwhsl_add_wishlist" class="not_signed_in">Please sign in to use this widget</span>';
            }
            else
            {
                if ($this -> zenwhsl_has_wishlisted($post_id, $user))
                {
                    echo '<span id="zenwhsl_add_wishlist" class="already_wishlisted">Already wishlisted!</span>';
                }
                else
                {
                    echo '<span id="zenwhsl_add_wishlist"><a id="zenwhsl_add_wishlist" href="#">Add to wishlist</a></span>';
                }
            }

            echo $after_widget;
        }
    }

    function zenwhsl_has_wishlisted($post_id, $user)
    {
        $values = get_user_meta($user -> ID, 'zenwhsl_wanted_posts');
        //echo sizeof($values);

        foreach($values as $value)
        {
            //echo $value;
            if($value == $post_id)
            {
                return true;
            }
        }
        return false;
    }
}

?>