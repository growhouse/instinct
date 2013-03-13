<?php

require_once(INSTINCT_ROOT."/hatches/post-title.hatch.php");
Instinct::hatch_register("InstinctHatchPostTitle");

require_once(INSTINCT_ROOT."/hatches/post-content.hatch.php");
Instinct::hatch_register("InstinctHatchPostContent");
       
require_once(INSTINCT_ROOT."/hatches/site-settings.hatch.php");
Instinct::hatch_register("InstinctHatchSiteName");
Instinct::hatch_register("InstinctHatchSiteDescription");

//require_once(INSTINCT_ROOT."/hatches/nav-menu.hatch.php");
//Instinct::hatch_register("InstinctHatchNavMenu");





