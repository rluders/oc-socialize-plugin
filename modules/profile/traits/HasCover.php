<?php

namespace RLuders\Socialize\Modules\Profile\Traits;

trait HasCover
{
    /**
     * Who is the base model?
     *
     * @return Model
     */
    public function getModel()
    {
        return $this;
    }

    /**
     * Returns the public image file path to this user's cover.
     */
    public function getCoverThumb($size = 1280, $options = null)
    {
        if (is_string($options)) {
            $options = ['default' => $options];
        } elseif (!is_array($options)) {
            $options = [];
        }

        $model = $this->getModel();
        if ($model->cover) {
            return $model->cover->getThumb($size, $size, $options);
        }

        // @TODO Can we return a default cover?
    }
}
