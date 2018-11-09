<?php

Route::group(
    [
        'prefix' => 'api/socialize/friend',
        'namespace' => 'RLuders\Socialize\Modules\Friendship\Http\Controllers'
    ],
    function () {
        Route::post('accept', 'AcceptFriendship');
        Route::post('cancel', 'CancelFriendship');
        Route::post('decline', 'DeclineFriendship');
        Route::post('remove', 'RemoveFriendship');
        Route::post('send', 'SendFriendship');
    }
);