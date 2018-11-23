<?php

namespace RLuders\Socialize\Models;

use Auth;
use Model;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Database\Eloquent\SoftDeletes;
use RLuders\Socialize\Modules\Activity\Traits\RecordsActivity;
use RLuders\Socialize\Models\File;

class Album extends Model
{
    use SoftDeletes;
    use RecordsActivity;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'rluders_socialize_albums';

    /**
     * One-to-One Relationships
     *
     * @var array
     */
    public $attachOne = [
        'cover' => [
            'RLuders\Socialize\Models\File',
            'delete' => true,
            'public' => false
        ],
        'cover_protected' => [
            'RLuders\Socialize\Models\File',
            'delete' => true,
            'public' => false
        ],
    ];

    /**
     * One-toMany Relationships
     *
     * @var array
     */
    public $morphMany = [
        'photos' => [
            'RLuders\Socialize\Models\File',
            'name' => 'attachment',
            'delete' => true,
            'public' => false
        ]
    ];

    /**
     * Fillable columns on table
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'privacy',
        'status',
        'user_id'
    ];

    /**
     * Enable/Disable timestamp at the table
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Who owns this Video
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('RainLab\User\Models\User');
    }

    public function getCover($width = 255, $height = 255)
    {
        $photo = $this->photos()->first();

        if (!$photo) {
            // @TODO Placeholder
            return "http://placeimg.com/{$width}/{$height}/any/{$this->id}";
        }

        if (!$this->cover) {
            $cover = new File(['data' => $photo->getLocalPath()]);
            $cover->disk_name = 'cover_'
                . $this->id
                . '_'
                . time()
                . '.jpg';
            $cover->save();
            $this->cover = $cover;
        }

        return $this->cover->getThumb($width, $height, ['mode' => 'crop']);
    }

    public function getProtectedCover($width = 255, $height = 255)
    {
        $photo = $this->photos()->first();

        if (!$photo) {
            // @TODO Placeholder
            return "http://placeimg.com/{$width}/{$height}/any/{$this->id}";
        }

        if (!$this->cover_protected) {

            $savePath = temp_path()
                . '/images/cover_protected_'
                . $this->id
                . '.jpg';

            $lockedPath = plugins_path()
                . '/rluders/socialize/assets/images/locked.png';

            if (!file_exists($savePath)) {
                $picture = Image::make($photo->getLocalPath())
                    ->fit(1200, 1200)
                    ->blur(45);
                $locked = Image::make($lockedPath);

                $canvas = Image::canvas(1200, 1200);
                $canvas->insert($picture, 'top-left');
                $canvas->insert($locked, 'top-left');
                $canvas->encode('jpg', 75)->save($savePath);

                $cover_protected = new File(['data' => $savePath]);
                $cover_protected->disk_name = 'cover_protected_'
                    . $this->id
                    . '_'
                    . time()
                    . '.jpg';
                $cover_protected->save();

                $this->cover_protected = $cover_protected;
            }

        }

        return $this->cover_protected->getThumb(
            $width,
            $height,
            ['mode' => 'crop']
        );
    }

    public function scopeOrderItems($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}