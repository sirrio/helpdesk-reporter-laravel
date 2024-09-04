<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'mathFractions',
        'mathLow',
        'mathHigh',
        'programming',
        'physics',
        'chemistry',
        'organization',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
