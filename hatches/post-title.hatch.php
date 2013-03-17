<?php

class InstinctHatchPostTitle extends InstinctHatch {

    public $hint = "Edit the title";
    public $title = "Title";
    public $imode = INSTINCT_IMODE_CHAMELEON;

    public function save($id, $data) {

        $post = get_post($id);

        if ($post->post_type == "post" || $post->post_type == "page") {
            $post->post_title = $data;
            wp_update_post($post);
            return new InstinctResponse($data, INSTINCT_STATUS_OK);
        }

        return new InstinctResponse($data, INSTINCT_STATUS_OK);
    }

    public function render_interface($id) {
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
            <input name="title" id="instinct-chameleon" value="<?php echo($p->post_title); ?>" style="width: 330px;"/>
            <a href="#" id="instinct-save" class="button button-primary">Save</a>
            <a href="#" id="instinct-close" class="button">Close</a>
        </form>
        <?php
        return ob_get_clean();
    }

    public static function hook() {
        add_filter("the_title", function($title, $id) {
                    $p = get_post($id);
                    if ($p->post_type == "post" || $p->post_type == "page")
                        return Instinct::inject("InstinctHatchPostTitle", $id, $title);
                    return $title;
                }, 999, 2);
    }

    public function is_allowed() {
        //$u = new WP_User(get_current_user_id());
        //var_dump($u->allcaps);
        $p = get_post($this->id);
        // echo($p->post_status);
        if ($p->post_type == "post") {
            if ($p->post_author == get_current_user_id()) {
                if ($p->post_status == "publish")
                    return current_user_can("edit_published_posts");
                else
                    return current_user_can("edit_posts");
            }
            else {
                if ($p->post_status == "publish")
                    return current_user_can("edit_published_posts") && current_user_can("edit_others_posts");
                else
                    return current_user_can("edit_others_posts");
            }
        }

        if ($p->post_type == "page") {
            if ($p->post_author == get_current_user_id()) {
                if ($p->post_status == "publish")
                    return current_user_can("edit_published_pages");
                else
                    return current_user_can("edit_pages");
            }
            else {
                if ($p->post_status == "publish")
                    return current_user_can("edit_published_pages") && current_user_can("edit_others_pages");
                else
                    return current_user_can("edit_others_pages");
            }
        }
    }

}
