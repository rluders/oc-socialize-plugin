<?php

namespace RLuders\Socialize\Modules\Friendship\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use RLuders\Socialize\Modules\Friendship\Actions\RemoveFriendship;

class RemoveFriendshipController extends BaseController
{
    /**
     * Controller constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(
        Request $request,
        RemoveFriendship $action
    ) {
        $userId = (int)$request->get('user');
        $response = $action->execute(['user_id' => $userId]);
        return response()->json($response);
    }
}