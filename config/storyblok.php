<?php

return [
    /**
     * Tokens are read only. Not need for .env
     * prod token: just for accessing published content
     * stage token: accesses published and draft content
     */
    'token' => env('APP_ENV') === 'production' ? 'r8QhIKFAjQwo1svTXNYWYgtt' : 'UbRZzA42LunTesYK35QiUQtt',
    'version' => env('APP_ENV') === 'production' ? 'published' : 'draft',
    'destination' => 'app'
];
