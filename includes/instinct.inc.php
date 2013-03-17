<?php
define("INSTINCT_SIG_OPEN", "%!%!");
define("INSTINCT_SIG_CLOSE", "%!%!");

define("INSTINCT_DEBUG_PARTCOMPILE", false);

class Instinct {

    public static $inhibit = false;
    private static $hatches = array();
    private static $interface_markup = "";
    private static $content_wrap = true;

    static function inject($handle, $id = false, $content = "") {
        global $post;

        if (!$id)
            $id = $post->ID;

        $hatch = new $handle($id);

        if (self::$inhibit || !$hatch->is_allowed())
            return $content;

        return INSTINCT_SIG_OPEN . urlencode($hatch->_tag($content)) . INSTINCT_SIG_CLOSE;
    }

    static function inject_parent($handle, $id = false, $content = "") {
        global $post;

        if (!$id)
            $id = $post->ID;

        $hatch = new $handle($id);

        if (self::$inhibit || !$hatch->is_allowed())
            return $content;

        return INSTINCT_SIG_OPEN . urlencode($hatch->_tag_parent($content)) . INSTINCT_SIG_CLOSE;
    }

    static function inject_adjacent($handle, $id = false) {
        global $post;

        if (!$id)
            $id = $post->ID;

        $hatch = new $handle($id);

        if (self::$inhibit || !$hatch->is_allowed())
            return $content;
        
        return INSTINCT_SIG_OPEN . urlencode($hatch->_tag_adjacent($content)) . INSTINCT_SIG_CLOSE;
    }

    static function compile($content) {
        $doc = phpQuery::newDocument($content);

        foreach (pq("body *") as $el) {
            foreach ($el->attributes as $attr => $val) {

                if (stristr($val->nodeValue, INSTINCT_SIG_OPEN) !== false) {
                    pq($el)->attr($attr, strip_tags(urldecode($val->nodeValue)));
                }
            }
        }

        $doc = preg_replace_callback("/" . INSTINCT_SIG_OPEN . "(.*)" . INSTINCT_SIG_CLOSE . "/", function($matches) {
                    return str_replace(INSTINCT_SIG_OPEN, "", str_replace(INSTINCT_SIG_OPEN, "", urldecode($matches[0])));
                }, $doc);

        if (defined("INSTINCT_DEBUG_PARTCOMPILE") && INSTINCT_DEBUG_PARTCOMPILE) {
            return $doc;
        }

        $doc = phpQuery::newDocument($doc);

        // Wrappables

        foreach (pq("instinct-wrap") as $el) {
            if (count(pq($el)->parents("head")) < 1) {
                pq($el)->parent()->attr("x-instinct-hatch", pq($el)->attr("x-instinct-hatch"));
            }

            pq($el)->replaceWith(pq($el)->html());
        }

        // Adjacents

        foreach (pq("instinct-adj") as $el) {
            pq($el)->next()->attr("x-instinct-hatch", pq($el)->attr("x-instinct-hatch"));
            pq($el)->next()->prepend(pq($el)->html());
            pq($el)->remove();
        }

        // Parents

        foreach (pq("instinct-parent") as $el) {
            pq($el)->parent()->attr("x-instinct-hatch", pq($el)->attr("x-instinct-hatch"));
            pq($el)->remove();
        }

        // Final pass, stop nesting  

        foreach (pq("[x-instinct-hatch]") as $el) {
            if (count(pq($el)->parents("[x-instinct-hatch]")) > 0) {
                pq($el)->removeAttr("x-instinct-hatch");
            }
        }

        // Now allow us to boot angular

        pq("body")->attr("ng-app", "instinct");
        pq("body")->attr("ng-controller", "editableCtrl");

        // Finally, inject hatch interfaces

        return $doc;
    }

    public static function content_wrap($content) {
        if (self::$content_wrap)
            return "<div>" . $content . "</div>";
        return $content;
    }

    public static function write_ajax_url() {
        ?>
        <script type="text/javascript">
                                                                
            var _INSTINCT_AJAX_URL = "<?php echo(INSTINCT_AJAX_URL); ?>";
                                                            
            jQuery(document).ready(function(){
                var instinct = angular.element("body").scope();
                                                                                                                                                    
                jQuery("#wp-admin-bar-instinct-edit-mode").live("click",function(e){
                    e.preventDefault();
                    if(instinct.edit_mode)
                        jQuery("span.instinct-adminbar-label",this).html("Quick Edit");
                    else
                        jQuery("span.instinct-adminbar-label",this).html("Stop Editing");
                                                                                                                                                    
                    instinct.toggle_edit_mode();
                });
                                                                
                                                                
                                                                
            });
                                                        
                                                            
                                                                                                                                                    
                                                                                                                                                    
        </script>
        <?php
    }

    public static function auto_inhibition() {
        add_action("wp", function() {
                    Instinct::$inhibit = true;
                }, 0); // Start of WP action, inhibit
        add_action("wp_head", function() {
                    Instinct::$inhibit = false;
                }, 999999); // End of WP_Head action, uninhibit
        add_action("wp_footer", function() {
                    Instinct::$inhibit = true;
                }, 0); // Start of WP_Footer action, inhibit
    }

    public function hatch_register($name, $fn, $settings = array()) {

        require_once($fn);

        if (is_array($name)) {
            foreach ($name as $N) {
                self::$hatches[$N] = $settings;
            }
        }
        else
            self::$hatches[$name] = $settings;
    }

    public static function add_toolbar_items($admin_bar) {
        if (!is_admin()) {
            $admin_bar->add_menu(array(
                'id' => 'instinct-edit-mode',
                'title' => '<!--<span class="ab-icon"></span> --><span class="instinct-adminbar-label">Quick Edit</span>',
                'href' => '#',
                'meta' => array(
                    'title' => __('Quick Edit'),
                ),
            ));
        }
    }

    public static function hatch_runhooks() {
        foreach (self::$hatches as $name => $settings) {
            $name::hook();
        }
    }

    public static function interface_chrome() {
        ?>
        <style type="text/css">
            iframe.instinct-interface
            {
                display: none;
                overflow: hidden;

            }

            iframe.instinct-interface.instinct-interface-fullscreen
            {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 99998 !important;
            }

            .instinct-hidden
            {
                visibility: hidden;
            }

            .wp-pointer-content
            {
                font-family: sans-serif;
                font-size: 13px;
                line-height: 1.4em;
                color: #333;

            }

            .wp-pointer-content p
            {
                padding: 0 15px;
                margin: 15px 0;
            }

            .wp-pointer-content h3
            {
                display: block;
                font-weight: bold;
                font-family: sans-serif;
                font-size: 15px;
            }

            .wp-pointer-buttons a
            {
                color: #aaa;
                font-weight: normal;
                font-family: sans-serif;
                font-size: 15px;

            }

            .wp-pointer-buttons a:hover
            {
                color: #8cc1e9;
            }

            #instinct-loader
            {
                position: fixed;
                top: 0;
                left: 0;
                background-color: rgba(51,51,51,0.75);
                color: #fff;
                width: 100%;
                height: 100%;
                display: none;
                z-index: 99998;
            }
            #instinct-load-message
            {

                display: table-cell;
                vertical-align: middle; 
                text-align: center; 
                font-family: sans-serif;
                font-size: 42px;

                text-shadow: 0px -1px 0px #333;

            }

            #instinct-load-message small
            {
                color: #fff;
                font-size: 13px;
                text-transform: uppercase;

            }

        </style>
        <iframe id="instinct-interface" class="instinct-interface" frameborder="0" src="" scrolling="no" allowtransparency="true">
        Your browser must support frames.
        </iframe>
        <div id="instinct-loader">
            <div id="instinct-load-message">
                <img src="<?php echo(plugins_url("img/logo-white.png", INSTINCT_FILE)); ?>" alt="Instinct" /><br /><br />
                Please Wait<br />

            </div>
        </div>

        <?php
    }

    public static function ignore_js_errors() {
        ?><script type="text/javascript">
                    function stoperror(e)
                    {
                                                                       
                        return true;
                    }
                    window.onerror=stoperror;
        </script>
        <?php
    }

    public static function is_active() {

        return !is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')) && is_user_logged_in() && current_user_can("edit_posts");
    }

}

add_action("init", function() {

            if (Instinct::is_active()) {

                Instinct::auto_inhibition();
                InstinctAjax::init();
                Instinct::hatch_runhooks();
                // Parser Hooks



                add_action("wp", function() {

                            ob_start(array("Instinct", "compile"));
                        });

                add_action("shutdown", function() {

                            ob_end_flush();
                        });


                add_action("wp_footer", array("Instinct", "interface_chrome"));

                // Environment Hooks

                add_action("wp_enqueue_scripts", function() {

                            //hook the pointer 

                            if (get_user_meta(get_current_user_id(), "instinct-tooltip-editmodeintro", true) == "") {
                                wp_enqueue_style('wp-pointer');
                                wp_enqueue_script('wp-pointer');

                                wp_enqueue_script("instinct-tooltips", plugins_url("js/instinct-tooltips.js", INSTINCT_FILE), array("jquery", "wp-pointer"));
                                add_user_meta(get_current_user_id(), "instinct-tooltip-editmodeintro", "1");
                            }

                            wp_enqueue_script('jquery-fonts', plugins_url("js/jquery.fonts.js", INSTINCT_FILE), array("jquery"));
                            wp_enqueue_script("jquery-ui-core");
                            wp_enqueue_script("angularjs", "https://ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js", array("jquery"));
                            wp_enqueue_script("jquery-animateshadow", plugins_url("js/jquery.animate-shadow-min.js", INSTINCT_FILE), array("jquery"));
                            wp_enqueue_script("instinct-core", plugins_url("js/instinct.core.js", INSTINCT_FILE), array("angularjs", "jquery-animateshadow", "jquery-ui-core"));
                        });

                add_action("wp_head", function() {

                            Instinct::write_ajax_url();
                        });



                add_action('admin_bar_menu', array('Instinct', 'add_toolbar_items'), 70);
            }
        });




register_deactivation_hook(INSTINCT_FILE, function() {
            delete_user_meta(get_current_user_id(), "instinct-tooltip-editmodeintro");
        });

if (!function_exists("is_login_page")) {

    function is_login_page() {
        return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
    }

}

add_action("setup_theme", function() {

            add_filter("theme_root", function($dir) {
                        global $wp;
                        //var_dump($wp);
                        //die;

                        if (isset($_REQUEST['ia']) || (is_array($wp->query_vars) && array_key_exists("instinctajax", $wp->query_vars)))
                            return plugins_url("template/", INSTINCT_FILE);
                        else
                            return $dir;
                    }, 999);
        });