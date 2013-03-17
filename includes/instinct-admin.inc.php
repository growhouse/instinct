<?php

class InstinctAdmin {

    static function menus() {
        add_menu_page("Instinct Configuration", "Instinct", "manage_options", "instinct-main", array("InstinctAdmin", "main_page"), "", 64);
    }

    static function main_page() {
        ?>
        <div class="wrap">
            <div id="icon-generic" class="icon32"><br></div><h2>Instinct Configuration</h2>
            <h3 class="title">Advanced Settings</h3>
            <form method="post" action="">
                <?php wp_nonce_field('instinct-settings-save'); ?>
                <input type="hidden" name="instinct-settings-save" value="1" />
                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row">Theme Compatibility</th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><span>Theme Compatibility</span></legend>
                                    <label for="auto_wrap">
                                        <input name="instinct-autowrap" type="checkbox" id="instinct-autowrap" value="1" <?php if(get_option("instinct-autowrap", "1") == "1"){ echo("checked=\"checked\"");} ?>>
                                        Add a <code>&lt;div&gt;</code> wrapper to editable areas.</label><br>
                                   <!-- <label for="fix_charset"><input name="fix_charset" type="checkbox" id="fix_charset" value="1" checked="checked"> Stop PHPQuery trying to fix the HTML charset automatically <strong>(recommended for HTML5 themes)</strong></label> -->
                                </fieldset></td>
                        </tr>


                    </tbody></table>


                <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p></form>
        </div>
        <?php
    }

    public static function save_config() {

        if (isset($_POST['instinct-settings-save']) && check_admin_referer('instinct-settings-save')) {
            
            if (isset($_POST['instinct-autowrap']))
                update_option("instinct-autowrap", "1");
            else
                update_option("instinct-autowrap", "0");
        }
    }

}

add_action("admin_menu", array("InstinctAdmin", "menus"));

add_action("admin_init", array("InstinctAdmin", "save_config"));