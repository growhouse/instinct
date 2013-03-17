<?php

class InstinctAjax {

    private static function validate_request($data) {
        if (!isset($data['ia']))
            return false;
        if (!isset($data['ih']))
            return false;
        if (!isset($data['ii']))
            return false;
        if (!InstinctHatch::exists($data['ih'])) // Very important for security
            return false;

        return true;
    }

    private static function router($data) {


        if (!is_user_logged_in())
            return new InstinctResponse("Not authenticated", INSTINCT_STATUS_NOTAUTH);

        if (!self::validate_request($data))
            return new InstinctResponse("Invalid Request", INSTINCT_STATUS_VALIDATIONERROR);
        
        Instinct::$inhibit = true;

        $hatch = new $data['ih'](); // Messy code - $data['ih'] assumed safe to instanciate after self::validate_request()

        switch ($data['ia']) {
            case "interface":
            default:
                return new InstinctResponse($data['ih']::render_interface($data['ii']), INSTINCT_STATUS_OK, $hatch->imode, $data['ih']::$title);
                break;
            case "save":
                return $hatch->save($data['ii'], $data['id']);
                break;
        }
    }

    public static function run($data) {

        $input = file_get_contents("php://input");

        $input = json_decode($input);

        if (array_key_exists("instinctajax", $data->query_vars)) {
            
            add_filter('show_admin_bar', '__return_false');
            
            
            
            if ($input !== null)
                die(self::router((array) $input)->compose());
            else
                die(self::router($data->query_vars)->compose());
            
        }
    }

    public static function init() {

        add_filter('rewrite_rules_array', array("InstinctAjax", "insert_rewrite_rules"));
        add_filter('query_vars', array("InstinctAjax", "insert_query_vars"));
        add_action('wp_loaded', array("InstinctAjax", "flush_rules"));

        add_action('parse_request', array("InstinctAjax", "run"));
    }

    // flush_rules() if our rules are not yet included
    public static function flush_rules() {
        $rules = get_option('rewrite_rules');

        if (!isset($rules['instinctajax'])) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }

// Adding a new rule
    public static function insert_rewrite_rules($rules) {
        $newrules = array();
        $newrules['instinctajax'] = 'index.php?instinctajax=1';
        return $newrules + $rules;
    }

// Adding the id var so that WP recognizes it
    public static function insert_query_vars($vars) {
        array_push($vars, 'ih', 'ia', 'ii', 'instinctajax');
        return $vars;
    }

}
