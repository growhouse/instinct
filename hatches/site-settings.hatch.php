<?php



class InstinctHatchSiteName extends InstinctHatch {
    
    public $title = "Site Name";
    public $hint = "Edit site name";
    public $imode = INSTINCT_IMODE_CHAMELEON;
    
    public function save($id, $data) {

        update_option("blogname", $data);
        return new InstinctResponse($data, INSTINCT_STATUS_OK);
    }
    
    public function render_interface($id) {
        
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                                        
                //console.log(instinct);
                jQuery("#instinct-save").click(function(e){
                    e.preventDefault();
                                            
                    instinct.savehatch(jQuery("input[name='title']").attr("value"));
                });
                                                        
            });
        </script>
        <form>
            <input name="title" id="instinct-chameleon" value="<?php echo(get_option("blogname")); ?>" style="width: 490px;" />
            <a href="#" id="instinct-save" class="button button-primary">Save</a>
            <a href="#" id="instinct-close" class="button">Close</a>
        </form>
        <?php
        return ob_get_clean();
    }
    
    public static function hook()
    {
        add_filter("option_blogname", function($val) {

            return Instinct::inject_parent("InstinctHatchSiteName", false). $val;
            
        }, 9999);
    }
}




class InstinctHatchSiteDescription extends InstinctHatch {
    
    public $title = "Site Description";
    public $hint = "Edit site description";
    public $imode = INSTINCT_IMODE_CHAMELEON;
    
    public function save($id, $data) {

        update_option("blogdescription", $data);
        return new InstinctResponse($data, INSTINCT_STATUS_OK);
    }
    
    public function render_interface($id) {
        
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                                        
                //console.log(instinct);
                jQuery("#instinct-save").click(function(e){
                    e.preventDefault();
                                            
                    instinct.savehatch(jQuery("input[name='title']").attr("value"));
                });
                                                        
            });
        </script>
        <form>
            <input name="title" id="instinct-chameleon" value="<?php echo(get_option("blogdescription")); ?>" style="width: 490px;" />
            <a href="#" id="instinct-save" class="button button-primary">Save</a>
            <a href="#" id="instinct-close" class="button">Close</a>
        </form>
        <?php
        return ob_get_clean();
    }
    
    public static function hook()
    {
        add_filter("option_blogdescription", function($val) {

            return Instinct::inject_parent("InstinctHatchSiteDescription", false). $val;
            
        }, 9999);
    }
}


