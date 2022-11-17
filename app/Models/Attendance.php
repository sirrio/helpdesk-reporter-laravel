<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'startTime',
        'endTime',
        'semester',
        'degree',
        'faculty',
        'mathBasic',
        'mathLow',
        'mathHigh',
        'programming',
        'physics',
        'chemistry',
        'organization',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
