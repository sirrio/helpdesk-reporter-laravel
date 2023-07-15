<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class SemesterController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Semester/ControlCenter', [
            'semesters' => Semester::orderBy('start', 'DESC')->get(),
        ]);
    }

    public function create(Request $request): RedirectResponse
    {
        Semester::create($request->validate([
            'semester' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]));

        return Redirect::back();
    }

    public function delete(Request $request): RedirectResponse
    {
        Semester::where('id', $request->id)->delete();

        return Redirect::back();
    }
}
