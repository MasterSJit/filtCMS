<?php

use Illuminate\Support\Facades\Schedule;

// Schedule the publication of scheduled content every minute
Schedule::command('filtcms:publish-scheduled')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
