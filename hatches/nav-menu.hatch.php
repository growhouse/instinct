<?php

add_filter("wp_nav_menu_items", function($menu, $args) {

            return Instinct::inject_parent("InstinctHatchNavMenu", $args->theme_location) . $menu;
        }, 999, 2);

class InstinctHatchNavMenu extends InstinctHatch {
    public static $title = "Menu";
    public $hint = "Edit this menu";
}

