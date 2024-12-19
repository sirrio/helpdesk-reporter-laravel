<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $semester = $request->query->get('semester');

        if (! $request->query->get('semester') || ! Semester::where('semester', $semester)->exists()) {
            return Redirect::route('statistics', ['semester' => Semester::orderBy('start', 'DESC')->first()->semester]);
        }

        $attendancesByWeek = Attendance::where('semester', $semester)->select('id', 'date')->get()->groupBy([
            'week' => function ($attendance) {
                return Carbon::parse($attendance->date)->format('W Y');
            },
            'day' => function ($attendance) {
                return Carbon::parse($attendance->date)->dayName;
            },
        ]);

        $attendancesByFaculty = Attendance::where('semester', $semester)->get()->countBy('faculty');

        $attendancesByDegree = Attendance::where('semester', $semester)->get()->countBy('degree');

        $topics = ['mathBasic', 'mathFractions', 'mathLow', 'mathHigh', 'programming', 'physics', 'chemistry', 'organization'];
        $selects = array_map(function($topic) {
            return "SUM(CASE WHEN {$topic} = 1 THEN 1 ELSE 0 END) as {$topic}";
        }, $topics);

        $attendancesByTopic = Attendance::where('semester', $semester)
            ->selectRaw(implode(", ", $selects))
            ->first()
            ->toArray();


        return Inertia::render('Statistics/Overview', [
            'attendancesByWeek' => $attendancesByWeek,
            'attendancesByFaculty' => $attendancesByFaculty,
            'attendancesByDegree' => $attendancesByDegree,
            'attendancesByTopic' => $attendancesByTopic,
            'semesters' => Semester::orderBy('start', 'DESC')->get(),
            'currentSem' => $request->query->get('semester'),
        ]);
    }
}
