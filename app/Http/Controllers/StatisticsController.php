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
        if (! $request->query->get('semester') || ! Semester::where('semester', $request->query->get('semester'))->exists()) {
            return Redirect::route('statistics', ['semester' => Semester::orderBy('start', 'DESC')->first()->semester]);
        }

        $attendancesByWeek = Attendance::where('semester', $request->query->get('semester'))->select('id', 'date')->get()->groupBy([
            'week' => function ($attendance) {
                return Carbon::parse($attendance->date)->format('W Y');
            },
            'day' => function ($attendance) {
                return Carbon::parse($attendance->date)->dayName;
            },
        ]);

        $attendancesByFaculty = Attendance::where('semester', $request->query->get('semester'))->get()->countBy('faculty');

        $attendancesByDegree = Attendance::where('semester', $request->query->get('semester'))->get()->countBy('degree');

        $topics = array_diff(Schema::getColumnListing('attendances'), // exclude all columns that aren't topics
            ['id', 'created_at', 'updated_at', 'user_id', 'semester', 'date', 'startTime', 'endTime', 'degree', 'faculty', 'deleted_at'] // exclude all columns that aren't topics
        );

        $attendancesByTopic = DB::table('attendances')
            ->selectRaw(implode(', ', array_map(function ($topic) {
                return "SUM(`{$topic}`) as `{$topic}`";
            }, $topics)))
            ->first();

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
