<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\UpdatePhotoRequest;

class UserController extends Controller
{
    public function updateUser(Request $request)
    {
        $user = $request->user();

        $data = $request->all();


        if ($request->has('password')) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return $this->success($user, 'Usuário atualizado com sucesso!', 200);
    }

    public function updatePhoto(UpdatePhotoRequest $request)
    {

        $user = $request->user();

        $photo = $request->file('photo_url');

       $path = $photo->store('photos', 'public');

        $user->photo_url = $path;

        $user->save();

        return $this->success($user, 'Foto atualizada com sucesso!', 200);

    }
}
