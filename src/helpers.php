<?php

use EthickS\FiltCMS\Services\FiltCMSService;

if (! function_exists('filtcms')) {
    /**
     * Get FiltCMS service instance.
     * 
     * @param string|null $slug Optional slug to pre-set for the instance
     * @param string|null $type Optional content type: 'blog', 'page', or 'category'
     * @return FiltCMSService
     * 
     * @example
     * // Without slug - traditional usage
     * filtcms()->blogTitle('my-blog');
     * 
     * // With slug - no need to pass slug in each method
     * $blog = filtcms('my-blog');
     * $blog->title();      // Gets blog title
     * $blog->body();       // Gets blog body
     * $blog->image();      // Gets blog image
     * 
     * // With explicit type
     * $page = filtcms('about-us', 'page');
     * $page->title();      // Gets page title
     */
    function filtcms(?string $slug = null, ?string $type = null): FiltCMSService
    {
        if ($slug !== null) {
            return FiltCMSService::get($slug, $type);
        }
        
        return app('filtcms');
    }
}
