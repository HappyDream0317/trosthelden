<?php

return [
    'tracking_enabled' => (bool)env('MATOMO_TRACKING_ENABLED', true),

    'tracking_id' => (string)env('MATOMO_TRACKING_ID'),

    'cross_site_linking_enabled' => (bool)env('MATOMO_TRACKING_CROSS_SITE_LINKING', false),

    'cross_site_linking_sites' => env('MATOMO_TRACKING_SITES'),

];
