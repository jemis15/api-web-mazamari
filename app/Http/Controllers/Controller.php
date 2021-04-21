<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected $admin_path = 'https://web.munimazamari.gob.pe/admin/contenido/archivos';
    
    public function saveImage($source, $directory)
    {
        // si el directorio donde se guardara la imagen no existe, lo creamos
        if (!is_dir($directory)) {
            mkdir($directory);
        }

        $destination = $directory . '/' . $this->getName($source);
        copy($source, $destination);
        return '/' . $destination;
    }

    public function getName($source)
    {
        $values = explode('/', $source);
        return array_pop($values);
    }
}
