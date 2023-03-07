<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
class UserController extends Controller
{
    public function index() {
        return Inertia::render('User/ControlCenter', ['users' => User::all()]);
    }

    public function changeMod(Request $request) {
        $user = User::where('id', $request->id)->first();
        $user->isMod = !$user->isMod;
        $user->save();

        return Redirect::back();
    }

    public function changeAdmin(Request $request) {
        $user = User::where('id', $request->id)->first();
        $user->isAdmin = !$user->isAdmin;
        $user->save();

        return Redirect::back();
    }

    public function delete(Request $request) {
        User::where('id', $request->id)->delete();

        return Redirect::back();
    }

    /**
     * @throws ValidationException
     */
    public function changePassword(Request $request) {
        $request->validate([
            'password' => [
            'required',
            'confirmed',
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols(),
            ]
        ]);

        $user = User::where('id', $request->id) -> first();
        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::back();
    }
}
