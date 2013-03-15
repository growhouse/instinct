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
                bottom: 20px;

                color: #333;


                text-align:center;
                font-size: 24px;
                width: 100%;
                font-family: "Arial", sans-serif;
                line-height: 40px;
                left: 0;
            }

            .instinct-hinter .message
            {
                display: inline-block;
                zoom: 0;
                position: relative;
                line-height: 40px;
                background-color: #ccc;
                border-radius: 4px;
                -moz-border-radius: 4px;
                -webkit-border-radius: 4px;

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
        <div class="instinct-hinter">
            <div class="message">{{ hint_msg }}</div>
        </div>
        <?php

    }

}

add_action("wp_footer", function() {
            InstinctHinter::render();
        });