<?php

class InstinctHatchPostContent extends InstinctHatch {

    public $hint = "Edit this post";
    public $title = "Edit post";

    public function edit() {
        return new InstinctResponse(ob_get_clean(), INSTINCT_STATUS_OK);
    }

    public function save($id, $data) {

        $post = get_post($id);

        if ($post->post_type == "post" || $post->post_type == "page") {
            $post->post_content = $data;
            wp_update_post($post);
            
            $postLoop = new WP_Query("p=".$id);
            
            if($postLoop->have_posts())
            {
                $postLoop->the_post();
                return new InstinctResponse(apply_filters('the_content',get_the_content()), INSTINCT_STATUS_OK);
            }
            
            return new InstinctResponse("Post not found", INSTINCT_STATUS_SERVERERROR);
            
        }

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
                                            
                    instinct.savehatch(tinymce.activeEditor.getContent());
                });
                
                
                
                
                                                        
            });
        </script>
        <?php
        $settings = array(
            'wpautop' => true,
            'media_buttons' => true,
            'tinymce' => array(
                'theme_advanced_buttons1' => 'bold,italic,underline,blockquote,|,undo,redo',
                'theme_advanced_buttons2' => '',
                'theme_advanced_buttons3' => '',
                'theme_advanced_buttons4' => ''
            ),
            'quicktags' => false
        );


        $thepost = get_post($id);

        echo '<form action="" method="post" target="_blank">';
        wp_editor($thepost->post_content, 'InstinctHatchPostContent');
        ?>
        <br />
            <a href="#" id="instinct-save" class="button button-primary">Save</a>
            <a href="#" id="instinct-close" class="button">Close</a>

           
        <?php
        echo '</form>';

        return ob_get_clean();
    }

    public static function hook()
    {
        add_filter("the_content", function($content) {
           
            $p = get_post();
            if ($p->post_type == "post" || $p->post_type == "page")
                return Instinct::inject("InstinctHatchPostContent", $post->id, $content);
            return $content;
        }, 9999); 

    }
    
}

