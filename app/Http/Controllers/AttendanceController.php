<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Degree;
use App\Models\Faculty;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->query->get('semester') || ! Semester::where('semester', $request->query->get('semester'))->exists()) {
            return Redirect::route('dashboard', ['semester' => Semester::orderBy('start', 'DESC')->first()->semester]);
        }

        if (Auth::user()->isAdmin) {
            $attendancesByDay = Attendance::where('semester', $request->query->get('semester'))->with('user')
                ->orderBy('date', 'DESC')
                ->get()
                ->groupBy(function ($data) {
                    return $data->date;
                });
        } else {
            $attendancesByDay = Attendance::where('user_id', Auth::user()->id)->where('semester', $request->query->get('semester'))->with('user')
                ->orderBy('date', 'DESC')
                ->get()
                ->groupBy(function ($data) {
                    return $data->date;
                });
        }

        return Inertia::render('Dashboard', [
            'attendancesByDay' => $attendancesByDay,
            'semesters' => Semester::orderBy('start', 'DESC')->get(),
            'currentSem' => $request->query->get('semester'),
            'faculties' => Faculty::orderBy('name')->get(),
            'degrees' => Degree::orderBy('name')->get(),
        ]);
    }

    public function store(AttendanceRequest $request): RedirectResponse
    {
        Attendance::create(['user_id' => Auth::id(), ...$request->validated()]);

        return Redirect::back();
    }

    public function attendance(Attendance $attendance): Response
    {
        return Inertia::render('Attendance/Index', [
            'attendance' => $attendance,
            'semesters' => Semester::orderBy('start', 'DESC')->get(),
            'faculties' => Faculty::orderBy('name')->get(),
            'degrees' => Degree::orderBy('name')->get(),
        ]);
    }

    public function update(AttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $data = $request->validated();

        $attendance->startTime = $data['startTime'];
        $attendance->endTime = $data['endTime'];
        $attendance->semester = $data['semester'];
        $attendance->degree = $data['degree'];
        $attendance->faculty = $data['faculty'];
        $attendance->mathBasic = $data['mathBasic'];
        $attendance->mathFractions = $data['mathFractions'];
        $attendance->mathLow = $data['mathLow'];
        $attendance->mathHigh = $data['mathHigh'];
        $attendance->programming = $data['programming'];
        $attendance->physics = $data['physics'];
        $attendance->chemistry = $data['chemistry'];
        $attendance->organization = $data['organization'];


        $attendance->save();

        return Redirect::route('dashboard');
    }

    public function delete(Request $request): RedirectResponse
    {
        Attendance::find($request->id)->delete();

        return Redirect::route('dashboard');
    }

    public function csv(Semester $semester)
    {
        $days = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag'];
        $table = Attendance::where('semester', $semester->semester)->get();
        $filename = 'export.csv';
        $handle = fopen($filename, 'w+');
        fwrite($handle, "sep=,\n");
        fputcsv($handle, [
            'Semester',
            'Wochentag',
            'Datum',
            'von',
            'bis',
            'Studiengang',
            'Fachrichtung',
            'Mathe Schulwissen',
            'Mathe Bruchrechnung',
            'Mathe Semester 1 und 2',
            'Mathe Semester 3+',
            'Programm.',
            'Physik',
            'Chemie',
            'Orga.',
            'Tutor',
        ]);

        foreach ($table as $row) {
            fputcsv($handle, [
                $row['semester'],
                $days[date('w', strtotime($row['date']))],
                $row['date'],
                $row['startTime'],
                $row['endTime'],
                $row['degree'],
                $row['faculty'],
                $row['mathBasic'] ? 'x' : '',
                $row['mathFractions'] ? 'x' : '',
                $row['mathLow'] ? 'x' : '',
                $row['mathHigh'] ? 'x' : '',
                $row['programming'] ? 'x' : '',
                $row['physics'] ? 'x' : '',
                $row['chemistry'] ? 'x' : '',
                $row['organization'] ? 'x' : '',
                User::find($row['user_id'])->name,
            ]);
        }

        fclose($handle);

        $headers = [
            'Content-Type' => 'text/csv',
        ];

        return \Illuminate\Support\Facades\Response::download($filename, 'export.csv', $headers);
    }
}
