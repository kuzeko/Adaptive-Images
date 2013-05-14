<?php
/* PROJECT INFO --------------------------------------------------------------------------------------------------------
   Version:   1.5.3
   Changelog: http://adaptive-images.com/changelog.txt

   Homepage:  http://adaptive-images.com
   GitHub:    https://github.com/MattWilcox/Adaptive-Images
   Twitter:   @responsiveimg

   Edited by: Kuzeko

   LEGAL:
   Adaptive Images by Matt Wilcox is licensed under a Creative Commons Attribution 3.0 Unported License.
--------------------------------------------------------------------------------------------------------------------- */

/* EXAMPLE -------------------------------------------------------------------------------------------------------------
/* Put this EXAMPLE up in the head of your <html> if you do not want to rely on Javascript -----------------------------

<noscript><style>
   @media only screen and (max-device-width: 479px) {
     html { background-image:url(adaptive-images-cookie.php?maxwidth=479); }
   }
   @media only screen and (min-device-width: 480px) and (max-device-width: 767px) {
     html { background-image:url(adaptive-images-cookie.php?maxwidth=767); }
   }
   @media only screen and (min-device-width: 768px) and (max-device-width: 991px) {
     html { background-image:url(adaptive-images-cookie.php?maxwidth=991); }
   }
   @media only screen and (min-device-width: 992px) and (max-device-width: 1381px) {
     html { background-image:url(adaptive-images-cookie.php?maxwidth=1381); }
   }
   @media only screen and (min-device-width: 1382px) {
     html { background-image:url(adaptive-images-cookie.php?maxwidth=unknown); }
   }
</style></noscript>
/* END EXAMPLE ------------------------------------------------------------------------------------------------------ */

/* CONFIG ----------------------------------------------------------------------------------------------------------- */

define('AI_VERSION', '1.5.3');
require_once('adaptive-images-config.php');

/* END CONFIG ------------------------------------------------------------------------------------------------------- */

// we need a number, so if it is undefined, give it something much bigger than the maximum as configured
$maxwidth = isset($_GET['maxwidth']) && is_numeric($_GET['maxwidth'])? intval($_GET['maxwidth']) : $resolutions[0]*2;

setcookie(COOKIE_NAME,$maxwidth,time()+$browser_cache,'/'); // set the cookie

// respond with an empty content
header('HTTP/1.1 204 No Content');
exit();
