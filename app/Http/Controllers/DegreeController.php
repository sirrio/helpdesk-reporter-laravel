<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use App\Models\Faculty;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DegreeController extends Controller
{
    public function index() {
        return Inertia::render('Degree/ControlCenter', [
            'degrees' => Degree::orderBy('name')->get(),
        ]);
    }

    public function create() {
        Degree::create(Request::capture()->validate([
            'name' => 'required',
        ]));

        return Redirect::back();
    }

    public function delete(Request $request) {
        Degree::where('id', $request->id)->delete();

        return Redirect::back();
    }}
