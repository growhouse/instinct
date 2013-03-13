<?php
define("INSTINCT_STATUS_OK", 1);
define("INSTINCT_STATUS_VALIDATIONERROR", 2);
define("INSTINCT_STATUS_SERVERERROR", 3);
define("INSTINCT_STATUS_NOTAUTH", 4);

class InstinctResponse {

    private $status;
    private $data_payload;
    private $output_type;

    function __construct($data, $status = INSTINCT_STATUS_OK, $output_type = "json") {
        $this->data_payload = $data;
        $this->status = $status;
        $this->output_type = $output_type;
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
        }
    }

    function to_iframe() {
        add_action("wp_enqueue_scripts", function() {
                    wp_enqueue_script("jquery");
                }, 99999);
        ob_start();
        ?><!DOCTYPE html>
        <html><head>
                <?php
                wp_head();
                ?>
                <script type="text/javascript">
                                    
                    var instinct = window.parent.angular.element("body").scope();
                                    
                    jQuery(window).load(function(){
                                                                                                            
                        var iframe = window.parent.jQuery("#instinct-interface");
                                                                     
                        instinct.update_hatch_element(jQuery(document).height());
                        iframe.css({height: jQuery(document).height()});
                                                                                                            
                    });
                                                                            
                                
                                                                                            
                                                                            
                                                                                            
                                                                                            
                                                                                                    
                </script>
                <style type="text/css">

                    html
                    {
                        margin-top: 0 !important;
                    }

                    body
                    {
                        

                    }

                    .instinct-interface-container
                    {
                        background-color: #fff;
                        border: 1px solid #aeaeae;
                        border-radius: 5px;
                        -moz-border-radius: 5px;
                        -webkit-border-radius: 5px;
                        padding: 5px;
                    }


                </style>
            </head>
            <body>
                <div class="instinct-interface-container">
                    <?php
                    echo $this->data_payload;
                    ?>
                </div>

                <?php
                wp_footer();
                ?>
            </body>
        </html><?php
        return ob_get_clean();
    }

}

