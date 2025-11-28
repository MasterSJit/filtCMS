<?php

use EthickS\FiltCMS\Services\FiltCMSService;

if (! function_exists('filtcms')) {
    /**
     * Get FiltCMS service instance.
     */
    function filtcms(): FiltCMSService
    {
        return app('filtcms');
    }
}
