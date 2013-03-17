<?php

define("INSTINCT_IMODE_JSON", "json");
define("INSTINCT_IMODE_UI", "iframe");
define("INSTINCT_IMODE_CHAMELEON", "chameleon");

class InstinctHatch {

    public $id = false;
    public $hint = false;
    public $allow_content_wrapping = true; // Set to false to exclude hatch from automatic content wrapping if enabled system-wide 
    public $title = "";
    public $imode = INSTINCT_IMODE_UI;
    
    static function exists($hatch_name)
    {
        // Return true if hatch exists and is valid, false if not
        
        if(class_exists($hatch_name))
        {
            if(in_array("InstinctHatch", class_parents($hatch_name)))
                    return true;
        }
        
        return false;
        
    }
    
    function __construct($id) {
        $this->id = $id;
    }

    function _tag($content = "") {
        if (!Instinct::$inhibit)
            return Instinct::content_wrap("<instinct-wrap x-instinct-hatch='" . $this->_data() . "'>" . $content . "</instinct-wrap>");
        else
            return $content;
    }

    function _tag_parent() {
        if (!Instinct::$inhibit)
            return "<instinct-parent x-instinct-hatch='" . $this->_data() . "'></instinct-parent>";
        else
            return "";
    }

    function _tag_adjacent($content = "") {
        if (!Instinct::$inhibit)
            return "<instinct-adj x-instinct-hatch='" . $this->_data() . "'></instinct-adj>" . $content;
        else
            return $content;
    }

    function _data() {
        $data = array(
            "hatch" => get_class($this),
            "id" => $this->id,
            "hint" => $this->hint,
            "imode" => $this->imode,
        );
        return json_encode((object) $data);
    }

    /*
     * Overridable function for displaying editor markup.
     */

    public function edit() {
        return new InstinctResponse("", INSTINCT_STATUS_OK);
    }

    /*
     * Overridable function for saving
     */

    public function save($id, $data) {
        return new InstinctResponse("", INSTINCT_STATUS_OK);
    }
    
    public function render_interface($id)
    {
        return "";
    }
    
    public static function hook()
    {
        
    }

}
