<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // GET v1/posts
    public function index(Request $request)
    {
        $skip = (int) $request->get('page', 0);
        $skip = $skip > 0 ? --$skip : 0;
        $limit = (int) $request->get('limit', 15);
        $limit = $limit > 0 ? $limit : 15;
        $tags = $request->get('tags') ? explode(',', $request->get('tags')) : [];
        $postsQuery = Post::query();
        $postsQuery->join('post_tipo', 'post_tipo.id', '=', 'posts.tipo_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->where('posts.produccion', 1)
            ->skip($skip * $limit)
            ->take($limit);

        if ($request->get('tipo')) {
            $postsQuery->where('tipo', $request->get('tipo'));
        }

        if (count($tags)) {
            $postsQuery->join('post_tag', 'post_tag.post_id', '=', 'posts.id');
            $postsQuery->whereIn('post_tag.tag_id', $tags);
        }

        $posts = $postsQuery
            ->orderBy('created_at', 'desc')
            ->get([
                'posts.id',
                'posts.titulo',
                'posts.image',
                'posts.video',
                'posts.image_of_video',
                'posts.video_from',
                'posts.produccion',
                'posts.created_at',
                'posts.tipo',
                'users.name as user_name',
                'users.image as user_image',
                'post_tipo.nombre as categoria'
            ]);

        return response()->json([
            'page' => $skip + 1,
            'total' => count($posts),
            'data' => $posts,
        ]);
    }

    // GET v1/posts/{id}
    public function show($id)
    {
        $post = Post::join('post_tipo', 'post_tipo.id', 'posts.tipo_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->select(
                'posts.id',
                'posts.titulo',
                'posts.image',
                'posts.video',
                'posts.contenido',
                'posts.tipo',
                'posts.image_of_video',
                'posts.video_from',
                'posts.produccion',
                'posts.created_at',
                'users.name as user_name',
                'users.image as user_image',
            )
            ->find($id);

        return response()->json($post);
    }

    // GET v1/posts/details/title?value={id}
    public function showByTitle(Request $request)
    {
        $post = Post::join('post_tipo', 'post_tipo.id', 'posts.tipo_id')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->select(
                'posts.id',
                'posts.titulo',
                'posts.image',
                'posts.video',
                'posts.contenido',
                'posts.tipo',
                'posts.image_of_video',
                'posts.video_from',
                'posts.produccion',
                'posts.created_at',
                'users.name as user_name',
                'users.image as user_image',
            )
            ->where('posts.titulo', $request->get('value'))
            ->first();

        if (!$post) {
            return response()->json(false);
        }

        return response()->json($post);
    }

    // POST v1/posts
    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required|max:150|unique:App\Models\Post',
            'tipo' => 'required'
        ]);

        try {
            $image = null;
            if ($request->post('image')) {
                $image = $this->saveImage($request->post('image'), 'storage/images/posts');
            }

            $post = new Post;
            $post->titulo = $request->post('titulo');
            $post->image = $image;
            $post->contenido = $request->post('contenido');
            $post->tipo = $request->post('tipo'); // video o image
            $post->image_of_video = $request->post('image_of_video');
            $post->video_from = $request->post('video_from');
            $post->produccion = $request->post('produccion', 0);
            $post->user_id = $request->post('user_id');
            $post->tipo_id = $request->post('tipo_id');
            $post->save();

            return response()->json($post);
        } catch (\Throwable $th) {
            return response()->json(['error' => ['message' => $th->getMessage()]]);
        }
    }
}
