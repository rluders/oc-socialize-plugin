<?php

Route::group(
    [
        'prefix' => 'api/socialize',
        'namespace' => 'RLuders\Socialize\Modules\Video\Http\Controllers'
    ],
    function () {
        Route::apiResource('videos', 'VideoController');
    }
);