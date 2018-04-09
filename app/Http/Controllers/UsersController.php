<?php

namespace App\Http\Controllers;

use App\Handles\ImageUploadHandle;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth', [
           'except'=>['show']
        ]);
    }

    public function show(User $user)
    {

       return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $userRequest, User $user)
    {

        $upload = new ImageUploadHandle();
        $data = $userRequest->all();
        if ($userRequest->avatar) {
            $avatar_url = $upload->save($userRequest->avatar, 'avatar', $user->id, 362);
            if ($avatar_url) {
                $data['avatar'] = $avatar_url['path'];
            }
        }
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功');
    }
}
