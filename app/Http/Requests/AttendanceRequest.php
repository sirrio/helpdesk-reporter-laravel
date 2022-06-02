<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date' => ['required'],
            'startTime' => ['required'],
            'endTime' => ['required'],
            'semester' => ['required'],
            'degree' => ['required'],
            'faculty' => ['required'],
            'mathBasic' => ['required'],
            'mathLow' => ['required'],
            'mathHigh' => ['required'],
            'programming' => ['required'],
            'physics' => ['required'],
            'chemistry' => ['required'],
            'organization' => ['required'],
        ];
    }
}
