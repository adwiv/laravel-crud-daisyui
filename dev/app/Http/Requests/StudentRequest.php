<?php

namespace App\Http\Requests;

use App\Enums\Degree;
use App\Enums\Likes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route()->parameter('student')?->id;
        $ignoreId = $studentId ? ",$studentId,id" : '';

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', "unique:students,email{$ignoreId}"],
            'phone' => ['required', 'string', 'max:10'],
            'date_of_birth' => ['required', 'date_format:Y-m-d'],
            'gender' => ['required', 'string', 'in:male,female'],
            'degree' => ['required', 'string', Rule::enum(Degree::class)],
            'likes' => ['required', 'string', Rule::enum(Likes::class)],
            'address' => ['required', 'string', 'max:1024'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            //'name' => '',
            //'email' => '',
            //'phone' => '',
            //'date_of_birth' => '',
            //'gender' => '',
            //'degree' => '',
            //'likes' => '',
            //'address' => '',
            //'is_active' => '',
        ];
    }
}
