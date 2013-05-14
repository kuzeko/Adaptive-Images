<?php
/* If this is requested directly, no content should be submitted */
if(!defined(AI_VERSION)){
    // respond with not found
    header('HTTP/1.1 404 Not Found');
    exit();
}

/* CONFIG ----------------------------------------------------------------------------------------------------------- */

define('AI_COOKIE_NAME', 'resolution');

$resolutions   = array(1382, 992, 768, 480); // the resolution break-points to use (screen widths, in pixels)
$cache_path    = dirname($_SERVER["PHP_SELF"])."/ai-cache"; // where to store the generated re-sized images. From your document root!
$jpg_quality   = 75; // the quality of any generated JPGs on a scale of 0 to 100
$sharpen       = TRUE; // Shrinking images can blur details, perform a sharpen on re-scaled images?
$watch_cache   = TRUE; // check that the adapted image isn't stale (ensures updated source images are re-cached)
$browser_cache = 60*60*24*7; // How long the BROWSER cache should last (seconds, minutes, hours, days. 7days by default)

/* END CONFIG ------------------------------------------------------------------------------------------------------- */
