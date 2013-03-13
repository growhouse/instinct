<?php

add_filter("wp_nav_menu", function($menu, $args) {

            return Instinct::inject_parent("InstinctHatchNavMenu", $args->theme_location) . $menu;
        }, 9999, 2);

class InstinctHatchNavMenu extends InstinctHatch {
    
}

