<?php

Route::group(
    [
        'prefix' => 'api/socialize/friend',
        'namespace' => 'RLuders\Socialize\Modules\Friendship\Http\Controllers'
    ],
    function () {
        Route::post('accept', 'AcceptFriendshipController');
        Route::post('cancel', 'CancelFriendshipController');
        Route::post('decline', 'DeclineFriendshipController');
        Route::post('remove', 'RemoveFriendshipController');
        Route::post('send', 'SendFriendshipController');
    }
);