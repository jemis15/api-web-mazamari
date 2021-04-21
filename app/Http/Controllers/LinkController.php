<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->directory = 'storage/images/links';
    }

    // GET v1/links
    public function index(Request $request)
    {
        $linkQuery = Link::query();
        if ($request->get('groupname')) {
            $linkQuery->where('groupname', $request->get('groupname'));
        }

        $links = $linkQuery->get(['id', 'name', 'image', 'redirect_to', 'groupname', 'created_at']);

        return response()->json(['data' => $links]);
    }

    // POST v1/links
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required'
        ]);

        // verificamos que la imagen a guardar existe
        if (file_exists($request->post('image'))) {
            try {
                $image = $this->saveImage($request->post('image'), $this->directory);

                $link = new Link();
                $link->name = $request->post('name');
                $link->image = $image;
                $link->redirect_to = $request->post('redirec_to');
                $link->groupname = $request->post('groupname');
                $link->save();

                return response()->json($link, 201);
            } catch (\Throwable $th) {
                return response()->json(['error' => ['message' => $th->getMessage()]]);
            }
        }

        return response('la imagen no existe en uploads.', 400);
    }

    // GET v1/links/{id}
    public function show($id)
    {
        $link = Link::find($id, ['id', 'name', 'image', 'redirect_to', 'groupname']);
        return response()->json($link);
    }

    // PUT v1/links/{id}
    public function update(Request $request, $id)
    {
        $this->validate($request, ['image' => 'required']);
        try {
            $image = $this->saveImage($request->post('image'), $this->directory);

            Link::where('id', $id)->update([
                'name' => $request->post('name'),
                'image' => $image,
                'redirect_to' => $request->post('redirect_to'),
                'groupname' => $request->post('groupname'),
            ]);
            return response()->json([
                'message' => 'Se actualizo con exito!!',
                'link_id' => $id
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => ['message' => $th->getMessage()]
            ]);
        }
    }
}
