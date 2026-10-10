### Theme screenshot
[LINK](https://www.pexels.com/photo/html-code-270366/); placeholder.

### SEO
The theme is designed to work with [The SEO Framework](https://wordpress.org/plugins/autodescription/) (Schema markup in header.php)

### Schema in header
`function_exists` check HAS to be loaded after `wp_head`, otherwise `tsf` (from The Seo Framework) might not be found.

### Why Customizer?
While favicon can be changed directly from admin panel, custom logo still has to go through customizer.

### [/src/assets/scss/utilities.css](https://github.com/Nikodemsky/blank-wptheme/blob/2deafa3a923ab0082caeeaecc66bf433a60f306b/src/assets/scss/utilities/_utilities.scss#L29): Why so much styling for "a" element in utilities? 
Mostly for fixing the flickering under different circumstances in chromium browsers.

### [/src/assets/scss/utilities.css](https://github.com/Nikodemsky/blank-wptheme/blob/2deafa3a923ab0082caeeaecc66bf433a60f306b/src/assets/scss/utilities/_utilities.scss#L6): Splide fix?
*Optional: Slider/Carousel module: https://splidejs.com/ - abandonware, but still works properly on most of the scenarios, bug-free.<br>
Later might switch to [Embla](https://www.embla-carousel.com/), but only after v9 goes stable.

### [/src/assets/scss/utilities.css](https://github.com/Nikodemsky/blank-wptheme/blob/2deafa3a923ab0082caeeaecc66bf433a60f306b/src/assets/scss/utilities/_utilities.scss#L9-L11): CF7 fix
*Optional: Spinner is messing up the margins for the submit button styling and :disabled is pretty much self-explanatory.

### [/src/assets/scss/utilities.css](https://github.com/Nikodemsky/blank-wptheme/blob/2deafa3a923ab0082caeeaecc66bf433a60f306b/src/assets/scss/utilities/_utilities.scss#L13-L19): Normalization fixes
* Custom fixes for normalization;
* As for commented overflow-x-hiddem, overflow with "hidden" value is messing up multiple of native CSS API's, so it's only there if actually needed.

### [/inc/exists-checks.php](https://github.com/Nikodemsky/blank-wptheme/blob/main/inc/exists-checks.php): optional, custom checks for post existence
Custom functionality - check for posts existence, including CPT's - cache/transient support included. 

Of course, if there is no active multi-language plugin, there's an easier and faster way:
```
$blogposts_count = wp_count_posts('post');
$blogposts_exists = $blogposts_count->publish > 0;
```
but it only checks for the default language.


### [/inc/id-helpers.php](https://github.com/Nikodemsky/blank-wptheme/blob/main/inc/id-helpers.php): Helpers for getting ID's

Two parts:
1. [Universal function to get translated post ID](https://github.com/Nikodemsky/blank-wptheme/blob/2deafa3a923ab0082caeeaecc66bf433a60f306b/inc/id-helpers.php#L4-L19), works both on WPML and Polylang
2. [Get ID by template](https://github.com/Nikodemsky/blank-wptheme/blob/2deafa3a923ab0082caeeaecc66bf433a60f306b/inc/id-helpers.php#L22-L55); natively WordPress has no such built-in functionality, so it's easy and fast way to get ID of the page, that uses specific template;<br>
obviously, if multiple pages uses same template, then only the first one is considered.