<?php

namespace App\Http\Controllers;

use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DegreeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Degree/ControlCenter', [
            'degrees' => Degree::orderBy('name')->get(),
        ]);
    }

    public function create(): RedirectResponse
    {
        Degree::create(Request::capture()->validate([
            'name' => 'required',
        ]));

        return Redirect::back();
    }

    public function delete(Request $request): RedirectResponse
    {
        Degree::where('id', $request->id)->delete();

        return Redirect::back();
    }
}
