<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('User/ControlCenter', ['users' => User::all()]);
    }

    public function changeMod(Request $request): RedirectResponse
    {
        $user = User::where('id', $request->id)->first();
        $user->isMod = ! $user->isMod;
        $user->save();

        return Redirect::back();
    }

    public function changeAdmin(Request $request): RedirectResponse
    {
        $user = User::where('id', $request->id)->first();
        $user->isAdmin = ! $user->isAdmin;
        $user->save();

        return Redirect::back();
    }

    public function delete(Request $request): RedirectResponse
    {
        User::where('id', $request->id)->delete();

        return Redirect::back();
    }
}
