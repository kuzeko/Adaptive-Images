# Adaptive Images

Is a solution to automatically create, cache, and deliver device-appropriate versions of your website's content images. 
It does not require you to change your mark-up. 
It is intended for use with [Responsive Designs](http://www.abookapart.com/products/responsive-web-design) and to be combined with [Fluid Image](http://unstoppablerobotninja.com/entry/fluid-images/) techniques.

## How To

Copy `adaptive-images.php`, `adaptive-images-config.php` and `.htaccess` into the root directory of your site. 
If you already have a `.htaccess` file __DO NOT OVERWRITE IT__, skip down to the advanced instructions.

You can modify the configuration options in the `adaptive-images-config.php` files.

The `adaptive-images.php` script will create an `ai-cache` folder in the location specified inside the configuration file.  
If you are extra paranoid about security you can have the ai-cache directory sit outside of your web-root so it's not publicly accessible. 
Just set the paths properly in the `.htaccess` file and the `.php` configuration file.

You do not need the `adaptive-images-cookie.php` file unless you are using the __alternate method__ (see below) of detecting the users screen size. 
_So delete it if you like, no one likes mess._


### Update your `.htacces`
*You already have a .htaccess file*
I strongly advise you to duplicate that file so you can revert to it if things go pear-shaped.
Open your existing `.htaccess` file and edit the contents. 
You'll need to look and see if there is a section that begins with the following:

    <IfModule mod_rewrite.c>

If there is, then you need to add the following lines into that block:

    # Adaptive-Images -----------------------------------------------------------------------------------
    # Add any directories you wish to omit from the Adaptive-Images process on a new line, as follows:
    # RewriteCond %{REQUEST_URI} !some-directory
    # RewriteCond %{REQUEST_URI} !another-directory
    RewriteCond %{REQUEST_URI} !assets
    # Send any GIF, JPG, or PNG request that IS NOT stored inside one of the above directories
    # to adaptive-images.php so we can select appropriately sized versions
    RewriteRule \.(?:jpe?g|gif|png)$ adaptive-images.php
    # END Adaptive-Images -------------------------------------------------------------------------------

Otherwise, the complete `<IfModule mod_rewrite.c>` bloc in your `.htaccess` should look like this

    <IfModule mod_rewrite.c>
      Options +FollowSymlinks
      RewriteEngine On
      # Adaptive-Images -----------------------------------------------------------------------------------
      # Add any directories you wish to omit from the Adaptive-Images process on a new line, as follows:
      # RewriteCond %{REQUEST_URI} !some-directory
      # RewriteCond %{REQUEST_URI} !another-directory
      RewriteCond %{REQUEST_URI} !assets
      # Send any GIF, JPG, or PNG request that IS NOT stored inside one of the above directories
      # to adaptive-images.php so we can select appropriately sized versions
      RewriteRule \.(?:jpe?g|gif|png)$ adaptive-images.php
      # END Adaptive-Images -------------------------------------------------------------------------------
    </IfModule>

_Instructions are in the file as comments (any line that starts with a # is a comment, and doesn't actually do anything)_

### Update your `<head>`

Copy the following Javascript into the `<head>` of your site. 
It MUST go in the head as the first bit of JS, before any other JS. 
This is because it needs to work as soon as possible, any delay wil have adverse effects.

    <script>document.cookie='resolution='+Math.max(screen.width,screen.height)+'; path=/';</script>


If you would like to take advantage of high-pixel density devices such as the iPhone4 or iPad3 Retina display you can use the following JavaScript instead. 
It will send higher-resolution images to such devices - be aware this will mean slower downloads for Retina users, but better images.

    <script>document.cookie='resolution='+Math.max(screen.width,screen.height)+("devicePixelRatio" in window ? ","+devicePixelRatio : ",1")+'; path=/';</script>

### Configurations

You can now open the `adaptive-images-config.php` file and have a play with the settings that are in the CONFIG area. By default it looks like this:

    /* CONFIG ----------------------------------------------------------------------------------------------------------- */
    define('AI_COOKIE_NAME', 'resolution');
    $resolutions   = array(1382, 992, 768, 480); // the resolution break-points to use (screen widths, in pixels)
    $cache_path    = dirname($_SERVER["PHP_SELF"])."/ai-cache"; // where to store the generated re-sized images. From your document root!
    $jpg_quality   = 75; // the quality of any generated JPGs on a scale of 0 to 100
    $sharpen       = TRUE; // Shrinking images can blur details, perform a sharpen on re-scaled images?
    $watch_cache   = TRUE; // check that the adapted image isn't stale (ensures updated source images are re-cached)
    $browser_cache = 60*60*24*7; // How long the BROWSER cache should last (seconds, minutes, hours, days. 7days by default)
    /* END CONFIG ------------------------------------------------------------------------------------------------------- */


-   `$resolutions` are the screen widths we'll work with. 
In the default it will store a re-sized image for large screens, normal screens, tablets, phones, and tiny phones.
In general, if you're using a responsive design in CSS, these breakpoints will be exactly the same as the ones you use in your media queries.

-   `$cache_path` If you don't like the cached images being written to that folder, you can put it somewhere else.
Just put the path to the folder here and make sure you create it on the server if for some reason that couldn't be done autmoatically by `adaptive-images.php`.

-    `$sharpen` Will perform a subtle sharpening on the rescaled images.
Usually this is fine, but you may want to turn it off if your server is very very busy.

-    `$watch_cache` If your server gets very busy it may help performance to turn this to FALSE. 
It will mean however that you will have to manually clear out the cache directory if you change a resource file


## Alternate method for those who can't rely on JavaScript

One of the weaknesses of the Adaptive Images process is its reliance on JavaScript to detect the visitors screen size, and set a cookie with that screen size inside it. 
The following is a solution for people who need the system to work without the use of JavaScript, but it does have a major caveat which is why this is not the default method, and why it is "unsupported" (I'm not going to troubleshoot problems you have with this method).

### The alternative method

__Into the head__, _above_ your normal CSS `<link>` tags, add the following:

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

If you use this method you will need to ensure the widths here match those in your `adaptive-images.php` file, you will also need to have the `adaptive-images-cookie.php` file in the root of your website (_no one else needs this file_).

### The caveat

Using this method additionally (or instead) of the JS method makes it likely that on the very first visit to your site, the images sent to the visitor will be the original full-scale versions. 
However, __ALL__ subsequent pages on your site will work properly. 
What that means is that, really, this solution is only viable if you've got `$mobile_first` set to FALSE. 
Otherwise, the majority of people who visit your site will experience images too small for their computer on the very first visit.

The reason is to do with how browsers load web pages. 
The first thing a browser does is load the HTML, in the order it's written - so for a normal AI install it loads the HTML and see's the embeded JavaScript and immediately executes that JavaScript - which writes the cookie. 
It then carries on loading the rest of the page in the order it finds it. 
Long story short - it means that when it finds an image tag and asks the server for it, it already has a cookie stored.

That's not likely to be the case if you use the CSS method. 
Because the CSS method relies on an external file - it has to ask the server for the "`background-image`", which is really just a bit of PHP to set a cookie. 
The problem is that when a browser loads external files like that, it doesn't stop loading the HTML, it carries on doing that at the same time as waiting for the external file.
Which means that it can get to an image tag before the server has finished loading that PHP file. 
This is only an issue on the very first page load, and AI has smart fallbacks to deal with a no-cookie situation.

## Troubleshooting

Most of the time people report problems it is due to one of two things:

1.   If images vanish, there is something wrong with your `.htaccess` configuration. 
This happens mostly on WordPress sites - it's because the server, and wordpress, have specific requirements that are different from most servers. 
You'll have to play about in the `.htaccess` file and read up on how to use `ModRewrite`.

2.   If you're seeing error images (big black ones) that's AI working, so your `.htaccess` is fine.
Read the messages on the image. 
Most of the time you'll only see this problem because your server requires less strict permissions to write to the disc. 
Try setting the `ai-cache` directory to `775`, and if all else fails use `777` - but be aware this is not very secure if you're on a shared server, and you ought to instead contact your administrator to get the server set up properly.


## Benefits

- Ensures you and your visitors are not wasting bandwidth delivering images at a higher resolution than the vistor needs.
- Will work on your existing site, as it requires no changes to your mark-up.
- Is device agnostic (it works by detecting the size of the visitors screen)
- Is CMS agnostic (it manages its own image re-sizing, and will work on any CMS or even on flat HTML pages)
- Is entirely automatic. Once added to your site, you need do no further work.
- Highly configurable
    - Set the resolutions you want to become adaptive (usually the same as the ones in your CSS @media queries)
    - Choose where you want the cached files to be stored
    - Configure directories to ignore (protect certain directories so AI is not applied to images within them)

Find out more, and view examples at [Adaptive Images](http://adaptive-images.com)

## Legal

Adaptive Images by Matt Wilcox is licensed under a [Creative Commons Attribution 3.0 Unported License](http://creativecommons.org/licenses/by/3.0)
