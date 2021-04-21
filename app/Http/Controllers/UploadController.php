<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Intervention\Image\ImageManagerStatic as Image;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!is_dir('storage/uploads')) {
            mkdir('storage/uploads');
        }
    }

    // POST v1/uploads/images
    public function uploadImage(Request $request)
    {
        try {
            // validamos que sea una imagen
            $extension = $request->file('image')->guessClientExtension();
            if (
                $extension !== 'jpg' &&
                $extension !== 'jpeg' &&
                $extension !== 'png' &&
                $extension !== 'gif' &&
                $extension !== 'svg'
            ) {
                throw new FileException("Se produjo un error al cargar su imagen. Verifique que su archivo sea una imagen válida y vuelva a intentarlo.", 1);
            }

            // nuevo nombre de la imagen
            $newName = uniqid() . '.' . $extension;

            // ruta donde se guardara el imagen
            Image::make($request->file('image'))->save("storage/uploads/$newName", 70);

            return response()->json([
                'name' => $newName,
                'extension' => $extension,
                'location' => 'storage/uploads/' . $newName,
                'fullpath' => $request->server('APP_URL') . "/storage/uploads/$newName"
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => ['message' => $th->getMessage()]], 400);
        }
    }
}
