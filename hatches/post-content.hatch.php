<?php

class InstinctHatchPostContent extends InstinctHatch {

    public $hint = "Edit this post";

    public function edit() {
        return new InstinctResponse(ob_get_clean(), INSTINCT_STATUS_OK);
    }

    public function save($id, $data) {

        $post = get_post($id);

        if ($post->post_type == "post" || $post->post_type == "page") {
            $post->post_content = $data;
            wp_update_post($post);
            return new InstinctResponse($data, INSTINCT_STATUS_OK);
        }

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
        <div class="wp-core-ui">
            <a href="#" id="instinct-save" class="button">Save</a>

        </div>    
        <?php
        echo '</form>';

        return ob_get_clean();
    }

}

add_filter("the_content", function($content, $id) {
            $p = get_post($id);
            if ($p->post_type == "post" || $p->post_type == "page")
                return Instinct::inject("InstinctHatchPostContent", $id, $content);
            return $content;
        }, 9999, 2);
