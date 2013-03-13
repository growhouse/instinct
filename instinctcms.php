<?php

/*
  Plugin Name: Instinct
  Plugin URI:
  Description: Instinct is an intelligent on-page editor plugin for Wordpress 3.5. Instinct automatically integrates with most themes to assist in a simple plug 'n' play installation. It's extendable too, so plugin developers can add on-page editor functionality easily to complement their core features.
  Author: Tom Lawton
  Version: 0.1
  Author URI:
 */

define("INSTINCT_ROOT", dirname(__FILE__));
define("INSTINCT_FILE", __FILE__);
define("INSTINCT_AJAX_URL", "/instinctajax");

require_once("lib/phpQuery.php");

require_once("includes/instinct.inc.php");
require_once("includes/instinct-response.inc.php");
require_once("includes/instinct-hatch.inc.php");
require_once("includes/instinct-hinter.inc.php");
require_once("includes/instinct-ajax.inc.php");

require_once("includes/hatch-suite.inc.php");
