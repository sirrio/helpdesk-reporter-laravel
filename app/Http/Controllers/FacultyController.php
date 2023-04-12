<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class FacultyController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Faculty/ControlCenter', [
            'faculties' => Faculty::orderBy('name')->get(),
        ]);
    }

    public function create(): RedirectResponse
    {
        Faculty::create(Request::capture()->validate([
            'name' => 'required',
        ]));

        return Redirect::back();
    }

    public function delete(Request $request): RedirectResponse
    {
        Faculty::where('id', $request->id)->delete();

        return Redirect::back();
    }
}
