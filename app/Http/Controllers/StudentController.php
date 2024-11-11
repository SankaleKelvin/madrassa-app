<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // $students = Student::all();
        $students = Student::join('locations', 'students.location_id', '=', 'locations.id')
            ->join('madrassas', 'students.madrassa_id', '=', 'madrassas.id')
            ->select('students.*', 'locations.name as location_name', 'madrassas.name as madrassa_name')
            ->get();
        if ($students) {
            return response()->json([
                "Students" => $students
            ], 200);
        } else {
            return response()->json([
                "Error" => "No student record was found."
            ], 404);
        }
    }

    public function createStudent(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'student_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'location_id' => 'required|integer|exists:locations,id',
            'madrassa_id' => 'required|integer|exists:madrassas,id'
        ]);

        if ($request->hasFile('student_photo')) {
            $filename = $request->file('student_photo')->store('posts', 'public');
        } else {
            $filename = Null;
        }

        $student = new Student;
        $student->first_name = $request->first_name;
        $student->last_name = $request->last_name;
        $student->student_name = $request->student_name;
        $student->student_photo = $filename;
        $student->location_id = $request->location_id;
        $student->madrassa_id = $request->madrassa_id;

        $result = $student->save();
        if ($result) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getStudent($id)
    {
        try {
            $student = Student::findorfail($id)
                ->join('locations', 'students.location_id', '=', 'locations.id')
                ->join('madrassas', 'students.madrassa_id', '=', 'madrassas.id')
                ->select('students.*', 'locations.name as location_name', 'madrassas.name as madrassa_name')
                ->findorfail($id);
            return response()->json($student);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Student not found'
            ], 404);
        }
    }

    public function updateStudent(Request $request, $id)
    {
        // \Log::info('Request Data -2 : ' . json_encode($request->all()));
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'student_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'location_id' => 'required|integer|exists:locations,id',
            'madrassa_id' => 'required|integer|exists:madrassas,id'
        ]);

        if ($request->hasFile('student_photo')) {
            $filename = $request->file('student_photo')->store('posts', 'public');
        } else {
            $filename = Null;
        }

        $student = Student::find($id);

        if ($student) {
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->student_name = $request->student_name;
            $student->student_photo = $filename;
            $student->location_id = $request->location_id;
            $student->madrassa_id = $request->madrassa_id;
            $student->save();
            return response()->json($student);
        } else {
            return response("Update unsuccessful, no such Student exists");
        }
    }

    function deleteStudent($id)
    {
        try {
            $student = Student::findorfail($id);
            if ($student) {
                $deletedStudent = $student;

                $student->delete();
                return response()->json($deletedStudent);
            } else {
                return response("Delete unsuccessful, no such Student exits");
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Student not found!'
            ], 404);
        }
    }


    function searchStudent($name)
    {
        try {
            $student = Student::where('student_name', 'like', '%' . $name . '%')->get();
            if ($student) {

                return response()->json($student);
            } else {
                return response("No such matches");
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Match not found!'
            ], 404);
        }
    }
}
