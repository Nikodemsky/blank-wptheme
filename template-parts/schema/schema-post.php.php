<script type="application/ld+json">
{
"@context": "https://schema.org",
"@type": "Article",
"headline": "<?php echo single_post_title(); ?>",
"description": "<?php echo the_seo_framework()->get_description(); ?>",
"image": "<?php echo get_the_post_thumbnail_url( '', 'full' ); ?>",  
"author": {
    "@type": "Organization",
    "name": " Business / Author name",
    "url": "https://website-url.com"
},  
"publisher": {
    "@type": "Organization",
    "name": "Business",
    "logo": {
    "@type": "ImageObject",
    "url": "<?php if (has_custom_logo()) : echo esc_url( wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0] ); endif; ?>"
    }
},
"datePublished": "<?php echo get_the_date('d.m.Y'); ?>",
"dateModified": "<?php echo the_modified_date('d.m.Y'); ?>"
}
</script>