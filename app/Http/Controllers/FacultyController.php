<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Faculty;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class FacultyController extends Controller
{
    public function index() {
        return Inertia::render('Faculty/ControlCenter', [
            'faculties' => Faculty::orderBy('name')->get()
        ]);
    }

    public function create() {
        Faculty::create(Request::capture()->validate([
            'name' => 'required',
        ]));

        return Redirect::back();
    }

    public function delete(Request $request) {
        Faculty::where('id', $request->id)->delete();

        return Redirect::back();
    }
}
