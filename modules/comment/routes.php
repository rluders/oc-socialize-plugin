<?php

Route::group(
    [
        'prefix' => 'api/socialize',
        'namespace' => 'RLuders\Socialize\Module\Comment\Http\Controllers'
    ],
    function () {
        Route::apiResource('comments', 'CommentController');
    }
);