<?php

Route::group(
    [
        'prefix' => 'api/socialize',
        'namespace' => 'RLuders\Socialize\Module\Album\Http\Controllers'
    ],
    function () {
        Route::apiResource('albums', 'AlbumController');
    }
);