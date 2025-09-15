### Theme screenshot
https://www.pexels.com/photo/html-code-270366/
it's heavy, but it looks cool; simple placeholder.

### SEO
The theme is designed to work the [The SEO Framework](https://wordpress.org/plugins/autodescription/) (Schema markups in header.php)

### Schema in header
`function_exists` check HAS to be loaded after `wp_head`, otherwise `tsf` (from The Seo Framework) might not be found.

### Why Customizer?
While you can change favicon directly from admin panel, Custom logo still has to go through customizer - even site-editor still have no support for this.

### utilities.css: Why so much code for "a" element styling in utilities? 
* Mostly for fixing the flickering under different circumstances in chromium browsers.
* As for .35s timing - it feels like sweet spot, for smooth and not too "drastic" transition.

### utilities.css: Splide fix?
https://splidejs.com/ - even tho it's abandoned, still works properly on most of the scenarios, bug-free. Not really fan of Embla or Glide and Blaze is abandonware too.

### utilities.css: CF7 fix
Spinner is messing up the margins for the submit button styling and :disabled is pretty much self-explanatory.

### utilities.css: Normalization fixes?
* Custom fixes for normalization, that are not so much needed. 
* As for commented overflow-x-hiddem, hiding overflow is messing up multiple of native CSS API's, so it's only here if actually needed.

### functions.php: Removal of WP version from RSS
Security measure.

### functions.php: `add_theme_support( 'custom-line-height' );`
For styling line-height in site-editor.

### functions.php: Removal of gutenberg assets
Because of that nothing related to Gutenberg will work properly on the frontend. By design.

### functions.php: Emojis removal
Bloatware.

### functions.php: Comments removal
Simple, 100%-proven way to remove comments system from Wordpress. No need for third-party plugins. By default comment system should be disabled and enabled only if project require to do so.

### functions.php: oembed change
Consider it as addon, not really keen on using the native oembed feature on the website. Still, we want it to work anywhere else tho.

### functions.php: RSS removal
Obscure functionality. No need for Wordpress to update those.

### functions.php: Widgets removal
Used only in very specific cases.

### functions.php: Really Simple Discovery removal
Bloatware.

### functions.php: Wordpress shortlink removal
Bloatware.

### functions.php: Jquery Migrate removal
It's not really needed anymore.

### functions.php: Admin widgets dashboard removal
Bloatware, that could potentially lag admin panel too much in certain cases.

### functions.php: Rest API links removal from header
No need to reveal those in header.

### functions.php: Self-pingbacks removal
Underutilized functionality to begin with.

### functions.php: built-in sitemap removal
Since theme is made to use with The SEO Framework, there's no need to have native sitemaps.

### functions.php: XMLRPC removal
Security measure.

### functions.php: Heartbeat interval change
Wordpress natievely checking this too often, it does affect performance.

### functions.php: Removal of some native image sizes
Space optimization, those are not really commonly used.

### functions.php Optional change image process engine
From my personal tests - GD works way faster, than the default one. It saves a lot of time.

### functions.php: optional, custom, cached checks for post existence
Custom functionality - check for posts existence from various types - cache included, before actually go through each loops. 
Made for better optimization.

Of course, if there is no translation plugin enabled, there's easier and faster way:
```
$blogposts_count = wp_count_posts('post');
$blogposts_exists = $blogposts_count->publish > 0;
```
but it only checks for default language.
