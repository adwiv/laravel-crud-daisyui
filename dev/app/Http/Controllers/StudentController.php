<?php

/** @noinspection PhpMissingReturnTypeInspection */

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        if ($term = $request->q) {
            $students = Student::where('name', 'like', "%$term%")->paginate();
        } else {
            $students = Student::paginate();
        }
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $student = null;
        $action = route('students.store');
        $referrer = request()->headers->get('referer');
        return view('students.edit', compact('student', 'action', 'referrer'));
    }

    public function store(StudentRequest $request)
    {
        $referrer = $request->get('_referrer');
        $redirectTo = $referrer ?: route('students.index');

        $fields = $request->validated();
        Student::create($fields);

        return redirect($redirectTo)->with('success', 'Student created successfully');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $action = route('students.update', $student);
        $referrer = request()->headers->get('referer');
        return view('students.edit', compact('student', 'action', 'referrer'));
    }

    public function update(StudentRequest $request, Student $student)
    {
        $fields = $request->validated();
        $student->update($fields);

        $referrer = $request->get('_referrer');
        $redirectTo = $referrer ?: route('students.index');
        return redirect($redirectTo)->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully');
    }
}
