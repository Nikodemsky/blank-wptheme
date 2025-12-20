### Theme screenshot
[LINK](https://www.pexels.com/photo/html-code-270366/); placeholder.

### SEO
The theme is designed to work the [The SEO Framework](https://wordpress.org/plugins/autodescription/) (Schema markup in header.php)

### Schema in header
`function_exists` check HAS to be loaded after `wp_head`, otherwise `tsf` (from The Seo Framework) might not be found.

### Why Customizer?
While you can change favicon directly from admin panel, custom logo still has to go through customizer.

### utilities.css: Why so much code for "a" element styling in utilities? 
Mostly for fixing the flickering under different circumstances in chromium browsers.

### utilities.css: Splide fix?
https://splidejs.com/ - even tho it's abandoned, still works properly on most of the scenarios, bug-free. Not really fan of [Embla](https://www.embla-carousel.com/) or [Glide](https://glidejs.com/); [Blaze](https://blaze-slider.dev/) would be good alternative as barebone template, but it's abandonware too.

### utilities.css: CF7 fix
Spinner is messing up the margins for the submit button styling and :disabled is pretty much self-explanatory.

### utilities.css: Normalization fixes?
* Custom fixes for normalization;
* As for commented overflow-x-hiddem, overflow with "hidden" value is messing up multiple of native CSS API's, so it's only there if actually needed.

### functions.php: optional, custom, cached checks for post existence
Custom functionality - check for posts existence from various types - cache included, before actually go through each loops. 

Of course, if there is no active multi-language plugin, there's an easier and faster way:
```
$blogposts_count = wp_count_posts('post');
$blogposts_exists = $blogposts_count->publish > 0;
```
but it only checks for default language.
