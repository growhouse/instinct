<?php

class InstinctHatchPostTitle extends InstinctHatch {

    public $hint = "Edit the title";
    public static $title = "Title";

    public function save($id, $data) {

        $post = get_post($id);

        if ($post->post_type == "post" || $post->post_type == "page") {
            $post->post_title = $data;
            wp_update_post($post);
            return new InstinctResponse($data, INSTINCT_STATUS_OK);
        }

        return new InstinctResponse($data, INSTINCT_STATUS_OK);
    }

    public static function render_interface($id) {
        $p = get_post($id);
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
            <input name="title" value="<?php echo($p->post_title); ?>" />
            <a href="#" id="instinct-save" class="button button-primary">Save</a>
            <a href="#" id="instinct-close" class="button">Close</a>
        </form>
        <?php
        return ob_get_clean();
    }

}

add_filter("the_title", function($title, $id) {
            $p = get_post($id);
            if ($p->post_type == "post" || $p->post_type == "page")
                return Instinct::inject("InstinctHatchPostTitle", $id, $title);
            return $title;
        }, 999, 2);