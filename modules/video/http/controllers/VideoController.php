<?php

namespace RLuders\Socialize\Modules\Video\Http\Controllers;

use Illuminate\Http\Request;
use RLuders\Socialize\Models\Video;
use Illuminate\Routing\Controller as BaseController;
use RLuders\Socialize\Modules\Video\Actions\ShowVideo;
use RLuders\Socialize\Modules\Video\Actions\ListVideos;
use RLuders\Socialize\Modules\Album\Actions\CreateVideo;
use RLuders\Socialize\Modules\Album\Actions\DeleteVideo;
use RLuders\Socialize\Modules\Video\Actions\UpdateVideo;

class VideoController extends BaseController
{
    public function index(ListVideos $action)
    {
        return $action->execute();
    }

    public function store(CreateVideo $action)
    {
        return $action->execute();
    }

    public function show(Video $video, ShowVideo $action)
    {
        return $action->execute($video);
    }

    public function update(Video $video, UpdateVideo $action)
    {
        return $action->execute($video);
    }

    public function destroy(Video $video, DeleteVideo $action)
    {
        return $action->execute($video);
    }
}