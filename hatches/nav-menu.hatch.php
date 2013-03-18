<?php

class InstinctHatchNavMenu extends InstinctHatch {
    public $title = "Menu";
    public $hint = "Edit this menu";
    
    public static function hook()
    {
        add_filter("wp_nav_menu", function($menu) {
            return Instinct::inject_parent("InstinctHatchNavMenu", $args->theme_location) . $menu;
        });
    }
    
     public function is_allowed()
    {
        return current_user_can("manage_options");
    }
}

