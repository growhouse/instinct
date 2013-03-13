<?php

add_filter("option_blogname", function($val) {

            return Instinct::inject_parent("InstinctHatchSiteName", false). $val;
            
        }, 9999);

class InstinctHatchSiteName extends InstinctHatch {
    
    public static $title = "Site Name";
    
    public function save($id, $data) {

        update_option("blogname", $data);
        return new InstinctResponse($data, INSTINCT_STATUS_OK);
    }
    
    public static function render_interface($id) {
        
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
            <input name="title" value="<?php echo(get_option("blogname")); ?>" />
            <a href="#" id="instinct-save" class="button button-primary">Save</a>
            <a href="#" id="instinct-close" class="button">Close</a>
        </form>
        <?php
        return ob_get_clean();
    }
}


add_filter("option_blogdescription", function($val) {

            return Instinct::inject_parent("InstinctHatchSiteDescription", false). $val;
            
        }, 9999);

class InstinctHatchSiteDescription extends InstinctHatch {
    
    public static $title = "Site Description";
    
    public function save($id, $data) {

        update_option("blogdescription", $data);
        return new InstinctResponse($data, INSTINCT_STATUS_OK);
    }
    
    public static function render_interface($id) {
        
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
            <input name="title" value="<?php echo(get_option("blogdescription")); ?>" />
            <a href="#" id="instinct-save" class="button button-primary">Save</a>
            <a href="#" id="instinct-close" class="button">Close</a>
        </form>
        <?php
        return ob_get_clean();
    }
}


