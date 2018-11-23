<?php

namespace RLuders\Socialize\Modules\Comment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use RLuders\Socialize\Models\Comment;

class CommentController extends BaseController
{
    public function index(ListComments $action)
    {
        return $action->execute();
    }

    public function store(CreateComment $action)
    {
        return $action->execute();
    }

    public function show(Comment $comment, ShowComment $action)
    {
        return $action->execute($comment);
    }

    public function update(Comment $comment, UpdateComment $action)
    {
        return $action->execute($comment);
    }

    public function destroy(Comment $comment, DeleteComment $action)
    {
        return $action->execute($comment);
    }
}