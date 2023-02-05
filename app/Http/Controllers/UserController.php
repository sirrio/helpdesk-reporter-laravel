<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
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
    public function changePassword(Request $request) {
        $user =  User::where('id', $request->data["id"]) -> first();
        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::back();
    }
}
