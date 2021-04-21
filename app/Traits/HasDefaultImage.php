<?php

namespace App\Traits;

/**
 * Imagen por defecto al usuario sin avatar
 */
trait HasDefaultImage
{
    public function getImage(string $altText)
    {
        if (!$this->image) {
            return "https://ui-avatars.com/api/?name=$altText&size=200&background=random";
        }
        return '/images/faces/' . $this->image;
    }
}
