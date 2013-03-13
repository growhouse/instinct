<?php

class InstinctHinter {

    static function render() {
        ?>
        <style>
            .instinct-hinter
            {
                display: none;
                position: fixed;
                z-index: 99999;
                bottom: 0px;
                background-color: #000;
                color: #fff;
                text-align:center;
                font-size: 24px;
                width: 100%;
                font-family: "Arial", sans-serif;
                line-height: 80px;
                left: 0;
            }
        </style> 
        <div class="instinct-hinter">
            <div class="message">{{ hint_msg }}</div>
        </div>
        <?php

    }

}
 
add_action("wp_footer", function() {
            InstinctHinter::render();
        });