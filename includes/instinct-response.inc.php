<?php
define("INSTINCT_STATUS_OK", 1);
define("INSTINCT_STATUS_VALIDATIONERROR", 2);
define("INSTINCT_STATUS_SERVERERROR", 3);
define("INSTINCT_STATUS_NOTAUTH", 4);

class InstinctResponse {

    private $status;
    private $data_payload;
    private $output_type;
    private $interface_title;

    function __construct($data, $status = INSTINCT_STATUS_OK, $output_type = "json", $title = "") {
        $this->data_payload = $data;
        $this->status = $status;
        $this->output_type = $output_type;
        $this->interface_title = $title;
    }

    function compose() {
        switch ($this->output_type) {
            case "json":
            default:
                return json_encode(array(
                            'status' => $this->status,
                            'data' => $this->data_payload
                        ));
                break;
            case "iframe":
                return $this->to_iframe();
                break;
            case "chameleon":
                return $this->to_chameleon();
                break;
        }
    }

    function to_chameleon() {
        add_action("wp_enqueue_scripts", function() {
                    wp_enqueue_script("jquery");
                    wp_enqueue_style("buttons");
                    //wp_enqueue_style("wp-admin");
                }, 99999);
        ob_start();
        ?><!DOCTYPE html>
        <html><head>
                <?php
                wp_head();
                ?>
                <script type="text/javascript">
                                                                                                                                            
                    var instinct = window.parent.angular.element("body").scope();
                    var iframe = window.parent.jQuery("#instinct-interface");
                    var loader = window.parent.jQuery("#instinct-loader");
                                                                    
                    jQuery(window).load(function(){
                                                                                                                                                                                                                    
                                                                                
                                                                                                                                                                             
                        instinct.update_hatch_element(jQuery(document).height());
                        iframe.css({height: jQuery(document).height()});
                                                                                                        
                        iframe.css({
                            display: "block",
                            visibility: "visible",
                            width: jQuery(document).width()
                        });
                        loader.fadeOut(300, function(){
                            loader.css({display: "none"});
                        });
                                          
                        window.parent.jQuery("link[href*='fonts.googleapis.com']").each(function(){
                                            
                            jQuery("<link/>", {
                                rel: "stylesheet",
                                type: "text/css",
                                href: jQuery(this).attr("href")
                            }).appendTo("head");
                                            
                        });
                        instinct.chameleon(jQuery("#instinct-chameleon"));                
                                                                                
                                                                               
                                                                                                                                                                                                                    
                    });
                                                                                                            
                    jQuery(document).ready(function(){
                        window.parent.jQuery("body").on("keyup",function(e){
                                            
                            if(e.keyCode == 27) { // ESCAPE 
                                e.preventDefault();
                                                                                                                                    
                                instinct.close_hatch();
                                window.parent.jQuery(".instinct-hinter").stop(true,true).fadeOut();  
                            }
                        });
                                        
                    jQuery("body").on("keyup",function(e){
                                            
                        if(e.keyCode == 27) { // ESCAPE 
                            e.preventDefault();
                                                                                                                                    
                            instinct.close_hatch();
                            window.parent.jQuery(".instinct-hinter").stop(true,true).fadeOut();  
                        }
                    });
                                                
                    jQuery("#instinct-close").click(function(e){
                        e.preventDefault();
                                                                                                                                    
                        instinct.close_hatch();
                    });
                                                                        
                                                                               
                    jQuery("form").submit(function(e){
                        e.preventDefault();
                        jQuery("#instinct-save").click();
                    });
                                                        
                    instinct.hint("Press enter to save, ESC to cancel");
                    window.parent.jQuery(".instinct-hinter").stop(true,true).fadeIn(100);
                                                                      
                    iframe.focus();
                    jQuery("#instinct-chameleon").focus();
                });
                                                                                                                            
                </script>
                <style type="text/css">

                    /* http://meyerweb.com/eric/tools/css/reset/ 
        v2.0 | 20110126
        License: none (public domain)
                    */

                    html, body, div, span, applet, object, iframe,
                    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
                    a, abbr, acronym, address, big, cite, code,
                    del, dfn, em, img, ins, kbd, q, s, samp,
                    small, strike, strong, sub, sup, tt, var,
                    b, u, i, center,
                    dl, dt, dd, ol, ul, li,
                    fieldset, form, label, legend,
                    table, caption, tbody, tfoot, thead, tr, th, td,
                    article, aside, canvas, details, embed, 
                    figure, figcaption, footer, header, hgroup, 
                    menu, nav, output, ruby, section, summary,
                    time, mark, audio, video {
                        margin: 0;
                        padding: 0;
                        border: 0;
                        font-size: 100%;
                        font: inherit;
                        vertical-align: baseline;
                    }
                    /* HTML5 display-role reset for older browsers */
                    article, aside, details, figcaption, figure, 
                    footer, header, hgroup, menu, nav, section {
                        display: block;
                    }
                    body {
                        line-height: 1;
                    }
                    ol, ul {
                        list-style: none;
                    }
                    blockquote, q {
                        quotes: none;
                    }
                    blockquote:before, blockquote:after,
                    q:before, q:after {
                        content: '';
                        content: none;
                    }
                    table {
                        border-collapse: collapse;
                        border-spacing: 0;
                    }                   

                    html
                    {
                        margin-top: 0 !important;
                    }

                    body
                    {
                        font-family: "Arial", "Helvetica Neue", "Helvetica", sans-serif;

                    }



                </style>
            </head>
            <body class="wp-core-ui">


                <?php
                echo $this->data_payload;
                ?>



                <?php
                wp_footer();
                ?>
            </body>
        </html><?php
        return ob_get_clean();
    }

    function to_iframe() {
        add_action("wp_enqueue_scripts", function() {
                    wp_enqueue_script("jquery");
                    wp_enqueue_style("buttons");
                    //wp_enqueue_style("wp-admin");
                }, 99999);
        ob_start();
                ?><!DOCTYPE html>
        <html><head>
                <?php
                wp_head();
                ?>
                <script type="text/javascript">
                                                                                                                                            
                var instinct = window.parent.angular.element("body").scope();
                var iframe = window.parent.jQuery("#instinct-interface");
                var loader = window.parent.jQuery("#instinct-loader");
                                                                    
                jQuery(window).load(function(){
                                                                                                                                                                                                                    
                                                                                
                                                                                                                                                                             
                    instinct.update_hatch_element(jQuery(document).height());
                    iframe.css({height: jQuery(document).height()});
                                                                                                        
                    iframe.css({
                        display: "block",
                        visibility: "visible",
                        width: jQuery(document).width()
                    });
                    loader.fadeOut(300, function(){
                        loader.css({display: "none"});
                    });
                                                                        
                                                                        
                                                                                
                                                                               
                                                                                                                                                                                                                    
                });
                                                                                                            
                jQuery(document).ready(function(){
                    window.parent.jQuery("body").on("keyup",function(e){
                                            
                        if(e.keyCode == 27) { // ESCAPE 
                            e.preventDefault();
                                                                                                                                    
                            instinct.close_hatch();
                            window.parent.jQuery(".instinct-hinter").stop(true,true).fadeOut();  
                        }
                    });
                                        
                    jQuery("body").on("keyup",function(e){
                                            
                        if(e.keyCode == 27) { // ESCAPE 
                            e.preventDefault();
                                                                                                                                    
                            instinct.close_hatch();
                            window.parent.jQuery(".instinct-hinter").stop(true,true).fadeOut();  
                        }
                    });
                                        
                    jQuery("#instinct-close").click(function(e){
                        e.preventDefault();
                                                                                                                                    
                        instinct.close_hatch();
                    });
                                                                        
                    jQuery("#InstinctHatchPostContent_fullscreen").live("click",function(){
                        instinct.toggle_fullscreen_hatch();
                    });
                                                                        
                });
                                                                                                                            
                </script>
                <style type="text/css">

                    /* http://meyerweb.com/eric/tools/css/reset/ 
        v2.0 | 20110126
        License: none (public domain)
                    */

                    html, body, div, span, applet, object, iframe,
                    h1, h2, h3, h4, h5, h6, p, blockquote, pre,
                    a, abbr, acronym, address, big, cite, code,
                    del, dfn, em, img, ins, kbd, q, s, samp,
                    small, strike, strong, sub, sup, tt, var,
                    b, u, i, center,
                    dl, dt, dd, ol, ul, li,
                    fieldset, form, label, legend,
                    table, caption, tbody, tfoot, thead, tr, th, td,
                    article, aside, canvas, details, embed, 
                    figure, figcaption, footer, header, hgroup, 
                    menu, nav, output, ruby, section, summary,
                    time, mark, audio, video {
                        margin: 0;
                        padding: 0;
                        border: 0;
                        font-size: 100%;
                        font: inherit;
                        vertical-align: baseline;
                    }
                    /* HTML5 display-role reset for older browsers */
                    article, aside, details, figcaption, figure, 
                    footer, header, hgroup, menu, nav, section {
                        display: block;
                    }
                    body {
                        line-height: 1;
                    }
                    ol, ul {
                        list-style: none;
                    }
                    blockquote, q {
                        quotes: none;
                    }
                    blockquote:before, blockquote:after,
                    q:before, q:after {
                        content: '';
                        content: none;
                    }
                    table {
                        border-collapse: collapse;
                        border-spacing: 0;
                    }                   

                    html
                    {
                        margin-top: 0 !important;
                    }

                    body
                    {
                        font-family: "Arial", "Helvetica Neue", "Helvetica", sans-serif;

                    }

                    .instinct-interface-container
                    {
                        background-color: #f5f5f5;
                        color: #333;
                        border: 1px solid #aeaeae;
                        border-radius: 5px;
                        -moz-border-radius: 5px;
                        -webkit-border-radius: 5px;
                        border-color: #dfdfdf;
                        -webkit-box-shadow: inset 0 1px 0 #fff;
                        box-shadow: inset 0 1px 0 #fff;

                        min-width: 350px;
                    }

                    .instinct-interface-content
                    {
                        padding: 10px;
                    }

                    .instinct-interface-container h1
                    {

                        color: #464646;
                        line-height: 40px;
                        margin-top: 0;

                        font-size: 18px;
                        font-weight: normal;
                        border-radius: 5px 5px 0 0;
                        -moz-border-radius: 5px 5px 0 0;
                        -webkit-border-radius: 5px 5px 0 0;
                        padding: 0 10px;

                        background: #f1f1f1;
                        background-image: -webkit-gradient(linear,left bottom,left top,from(#ececec),to(#f9f9f9));
                        background-image: -webkit-linear-gradient(bottom,#ececec,#f9f9f9);
                        background-image: -moz-linear-gradient(bottom,#ececec,#f9f9f9);
                        background-image: -o-linear-gradient(bottom,#ececec,#f9f9f9);
                        background-image: linear-gradient(to top,#ececec,#f9f9f9);

                        border-bottom: 1px solid #dfdfdf;

                        text-shadow: #fff 0 1px 0;
                        -webkit-box-shadow: 0 1px 0 #fff;
                        box-shadow: 0 1px 0 #fff;


                    }




                </style>
            </head>
            <body>

                <div class="instinct-interface-container wp-core-ui">
                    <h1><?php echo($this->interface_title); ?></h1>
                    <div class="instinct-interface-content">
                        <?php
                        echo $this->data_payload;
                        ?>
                    </div>
                </div>

                <?php
                wp_footer();
                ?>
            </body>
        </html><?php
        return ob_get_clean();
    }

}

