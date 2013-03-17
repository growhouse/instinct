<?php

class InstinctHatchNavMenu extends InstinctHatch {
    public $title = "Menu";
    public $hint = "Edit this menu";
    
    public static function hook()
    {
        add_filter("wp_nav_menu_items", function($menu, $args) {

            return Instinct::inject_parent("InstinctHatchNavMenu", $args->theme_location) . $menu;
        }, 999, 2);
    }
}

