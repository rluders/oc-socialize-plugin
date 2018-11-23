<?php

namespace RLuders\Socialize\Modules\Album\Http\Controllers;

use Illuminate\Http\Request;
use RLuders\Socialize\Models\Album;
use Illuminate\Routing\Controller as BaseController;
use RLuders\Socialize\Modules\Album\Actions\ShowAlbum;
use RLuders\Socialize\Modules\Album\Actions\ListAlbums;
use RLuders\Socialize\Modules\Album\Actions\CreateAlbum;
use RLuders\Socialize\Modules\Album\Actions\DeleteAlbum;
use RLuders\Socialize\Modules\Album\Actions\UpdateAlbum;

class AlbumController extends BaseController
{
    public function index(ListAlbums $action)
    {
        return $action->execute();
    }

    public function store(CreateAlbum $action)
    {
        return $action->execute();
    }

    public function show(Album $album, ShowAlbum $action)
    {
        return $action->execute($album);
    }

    public function update(Album $album, UpdateAlbum $action)
    {
        return $action->execute($album);
    }

    public function destroy(Album $album, DeleteAlbum $action)
    {
        return $action->execute($album);
    }
}