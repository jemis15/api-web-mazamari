<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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

    // POST v1/users/whoami
    public function whoami(Request $request)
    {
        $user = User::where('token', $request->query('token'))
            ->first(['id', 'name', 'image', 'is_admin', 'nickname']);
        if ($user) {
            return response()->json($user);
        }

        return response()->json([
            'error' => ['message' => 'No estas logeado o session expirada']
        ], 400);
    }

    // GET v1/users
    public function index(Request $request)
    {
        $userQuery = User::query();
        if ($request->get('name')) {
            $userQuery->where('name', 'like', '%' . $request->query('name') . '%');
        }
        if ($request->get('nickname')) {
            $userQuery->where('nickname', 'like', '%' . $request->query('nickname') . '%');
        }
        $userQuery->skip($request->get('offset', 0))
            ->take($request->get('limit', 10))
            ->orderBy('name');

        // imagen por defecto
        $users = $userQuery->get([
            'id',
            'name',
            'image',
            'nickname',
            'is_admin',
            'created_at',
        ]);
        foreach ($users as $user) {
            $user->image = $user->getImage($user->name);
        }
        return response()->json($users);
    }

    // GET v1/users/{id}
    public function show($id)
    {
        $user = User::find($id, [
            'id',
            'name',
            'image',
            'nickname',
            'is_admin',
            'created_at'
        ]);
        if ($user) {
            $user->image = $user->getImage($user->name);
        }

        return response()->json($user);
    }

    // POST v1/users
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'email|unique:App\Models\User|max:100',
            'nickname' => 'required|unique:App\Models\User|max:30',
            'password' => 'required'
        ]);

        try {
            $user = new User;
            $user->name = $request->query('name');
            $user->nickname = $request->query('nickname');
            $user->email = $request->query('email');
            $user->password = password_hash($request->query('password'), PASSWORD_DEFAULT);
            $user->token = password_hash($request->query('nombre'), PASSWORD_DEFAULT);
            $user->is_admin = $request->query('is_admin', 0);
            $user->save();

            return response()->json($user, 201);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => ["message" => $th->getMessage()]
            ], 400);
        }
    }

    // PUT v1/users/{id}
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'max:50']
        ]);
        $userUpdate = User::where('id', $id)->update($request->all());
        if ($userUpdate) {
            return response()->json([
                'message' => 'Actualizado exitosamente',
                'user_id' => $id
            ]);
        } else {
            return response()->json([
                'error' => ['message' => 'Ninguna fila afectada']
            ], 400);
        }
    }

    // PUT v1/users/{id}/attributes/{attriute}
    public function updateAttributes(Request $request, $id, $attribute)
    {
        switch ($attribute) {
            case 'name':
                $this->validate($request, ['name' => 'required|max:50']);
                break;
            case 'email':
                $this->validate($request, ['email' => 'required|email|max:100']);
                break;
            case 'image':
                break;
            case 'nickname':
                $this->validate($request, ['nickname' => 'required|unique:App\Models\User|max:30']);
                break;
            case 'password':
                $this->validate($request, ['password' => 'required']);
                break;

            default:
                return response('El atributo ' . $attribute . ' no existe', 404);
        }

        $value = $attribute === 'password' ? password_hash($request->query('password'), PASSWORD_DEFAULT) : $request->query($attribute);
        $updatedUser = User::where('id', $id)->update([$attribute => $value]);

        if ($updatedUser) {
            return response()->json([
                'message' => 'Actualizado exitosamente',
                'user_id' => $id
            ]);
        } else {
            return response()->json([
                'error' => ['message' => 'Ninguna fila afectada'],
                'user_id' => $id
            ]);
        }
    }

    // DELETE v1/users/{id}
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response('No existe un usuario con id ' . $id, 404);
        }
        $user->delete();
        return response()->json([
            'message' => 'Borrado exitosamente',
            'user_id' => $id
        ]);
    }
}
