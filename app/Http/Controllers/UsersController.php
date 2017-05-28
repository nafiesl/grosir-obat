<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $editableUser = null;
        $users = User::get();

        if (in_array($request->get('action'), ['edit', 'delete']) && $request->has('id')) {
            $editableUser = User::find($request->get('id'));
        }

        return view('users.index', compact('users', 'editableUser'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|max:60',
            'username' => 'required|max:30',
            'password' => 'nullable|between:5,15',
        ]);

        $newUserData = $request->only('name', 'username');

        if ($request->has('password')) {
            $newUserData['password'] = $request->get('password');
        } else {
            $newUserData['password'] = 'rahasia';
        }

        $user = User::create($newUserData);

        flash(trans('user.created'), 'success');

        return redirect()->route('users.index');
    }

    public function update(Request $request, $userId)
    {
        $this->validate($request, [
            'name'     => 'required|max:60',
            'username' => 'required|max:30|unique:users,username,'.$request->segment(2),
            'password' => 'nullable|between:5,15',
        ]);

        $userData = $request->only('name', 'username');
        if ($request->has('password')) {
            $userData['password'] = $request->get('password');
        }

        User::findOrFail($userId)->update($userData);

        flash(trans('user.updated'), 'success');

        return redirect()->route('users.index');
    }

    public function destroy(Request $request, User $user)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id|not_exists:transactions,user_id',
        ], [
            'user_id.not_exists' => trans('user.undeleted'),
        ]);

        if ($request->get('user_id') == $user->id && $user->delete()) {
            flash(trans('user.deleted'), 'success');

            return redirect()->route('users.index');
        }

        flash(trans('user.undeleted'), 'error');

        return back();
    }
}
