<?php
define("INSTINCT_SIG_OPEN", "%!%");
define("INSTINCT_SIG_CLOSE", "%!%");

class Instinct {

    public static $inhibit = false;
    private static $hatches = array();
    private static $interface_markup = "";

    static function inject($handle, $id = false, $content = "") {
        if (is_admin() || !is_user_logged_in())
            return $content;

        global $post;

        if (!$id)
            $id = $post->ID;

        $hatch = new $handle($id);
        return INSTINCT_SIG_OPEN . urlencode($hatch->_tag($content)) . INSTINCT_SIG_CLOSE;
    }

    static function inject_parent($handle, $id = false, $content = "") {
        if (is_admin() || !is_user_logged_in())
            return $content;

        global $post;

        if (!$id)
            $id = $post->ID;

        $hatch = new $handle($id);
        return INSTINCT_SIG_OPEN . urlencode($hatch->_tag_parent($content)) . INSTINCT_SIG_CLOSE;
    }

    static function inject_adjacent($handle, $id = false) {
        if (is_admin() || !is_user_logged_in())
            return;

        global $post;

        if (!$id)
            $id = $post->ID;

        $hatch = new $handle($id);
        return INSTINCT_SIG_OPEN . urlencode($hatch->_tag_adjacent($content)) . INSTINCT_SIG_CLOSE;
    }

    static function compile($content) {
        if (!is_user_logged_in())
            return $content;
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

    public static function write_ajax_url() {
        ?>
        <script type="text/javascript">
            var _INSTINCT_AJAX_URL = "<?php echo(INSTINCT_AJAX_URL); ?>";
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
        //add_action("wp_footer", function(){ Instinct::$inhibit = true; }, 0); // Start of WP_Footer action, inhibit
    }

    public function hatch_register($name, $settings = array()) {
        self::$hatches[$name] = $settings;
    }

    public static function add_toolbar_items($admin_bar) {
        $admin_bar->add_menu(array(
            'id' => 'instinct-edit-mode',
            'title' => 'Edit Mode',
            'href' => 'javascript: _INSTINCT_EDIT_MODE = true;',
            'meta' => array(
                'title' => __('Edit Mode'),
            ),
        ));
    }

    public static function interface_chrome() {
        ?>
        <style type="text/css">
            iframe.instinct-interface
            {
                display: none;
                overflow: hidden;
            }

            .instinct-hidden
            {
                visibility: hidden;
            }

        </style>
        <iframe id="instinct-interface" class="instinct-interface" frameborder="0" src="" scrolling="no">
        Your browser must support frames.
        </iframe>

        <?php
    }

}

add_action("init", function() {
            if (is_user_logged_in()) {

                Instinct::auto_inhibition();
                InstinctAjax::init();

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

                            wp_enqueue_script("angularjs", "https://ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js", array("jquery"));
                            wp_enqueue_script("jquery-animateshadow", plugins_url("js/jquery.animate-shadow-min.js", INSTINCT_FILE), array("jquery"));
                            wp_enqueue_script("instinct-core", plugins_url("js/instinct.core.js", INSTINCT_FILE), array("angularjs", "jquery-animateshadow"));
                        });

                add_action("wp_head", function() {

                            Instinct::write_ajax_url();
                        });

                add_action('admin_bar_menu', array('Instinct', 'add_toolbar_items'), 100);
            }
        });

add_action("setup_theme", function() {
            if (is_user_logged_in()) {
                add_filter("theme_root", function($dir) {
                            global $wp;
                            //var_dump($wp);
                            //die;

                            if (isset($_REQUEST['ia']) || array_key_exists("instinctajax", $wp->query_vars))
                                return plugins_url("template/", INSTINCT_FILE);
                            else
                                return $dir;
                        }, 999);
            }
        });



