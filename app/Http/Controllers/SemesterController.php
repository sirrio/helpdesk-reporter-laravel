<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class SemesterController extends Controller
{
    public function index()
    {
        return Inertia::render('Semester/ControlCenter', [
            'semesters' => Semester::orderBy('start', 'DESC')->get(),
        ]);
    }

    public function create(Request $request)
    {
        Semester::create($request->validate([
            'semester' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]));

        return Redirect::back();
    }

    public function delete(Request $request)
    {
        Semester::where('id', $request->id)->delete();

        return Redirect::back();
    }
}
