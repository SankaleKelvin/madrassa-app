<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StudentCourse;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function index()
    {
        // $studentcourses = StudentCourse::all();
        $studentcourses = StudentCourse::join('students', 'student_courses.student_id', '=', 'students.id')
            ->join('courses', 'student_courses.course_id', '=', 'courses.id')
            ->select('student_courses.*', 'students.student_name as student_name', 'courses.course_name as course_name')
            ->get();
        if ($studentcourses) {
            return response()->json([
                "StudentCourses" => $studentcourses
            ], 200);
        } else {
            return response()->json([
                "Error" => "No studentcourse record was found."
            ], 404);
        }
    }

    public function createStudentCourse(Request $request)
    {
        $request->validate([
            'enrollment_date' => 'required|string|max:255',
            'amount_charged' => 'required|numeric|max:25500',
            'student_id' => 'required|integer|exists:students,id',
            'course_id' => 'required|integer|exists:courses,id'
        ]);

        $studentcourse = new StudentCourse;
        $studentcourse->enrollment_date = $request->enrollment_date;
        $studentcourse->amount_charged = $request->amount_charged;
        $studentcourse->student_id = $request->student_id;
        $studentcourse->course_id = $request->course_id;

        $result = $studentcourse->save();
        if ($result) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getStudentCourse($id)
    {
        try {
            $studentcourse = StudentCourse::findorfail($id)
                ->join('students', 'student_courses.student_id', '=', 'students.id')
                ->join('courses', 'student_courses.course_id', '=', 'courses.id')
                ->select('student_courses.*', 'students.student_name as student_name', 'courses.course_name as course_name')
                ->findorfail($id);
            return response()->json($studentcourse);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'StudentCourse not found'
            ], 404);
        }
    }

    public function updateStudentCourse(Request $request, $id)
    {
        // \Log::info('Request Data -2 : ' . json_encode($request->all()));
        $request->validate([
            'enrollment_date' => 'required|string|max:255',
            'amount_charged' => 'required|numeric|max:25500',
            'student_id' => 'required|integer|exists:students,id',
            'course_id' => 'required|integer|exists:courses,id'
        ]);

        $studentcourse = StudentCourse::find($id);

        if ($studentcourse) {
            $studentcourse->enrollment_date = $request->enrollment_date;
            $studentcourse->amount_charged = $request->amount_charged;
            $studentcourse->student_id = $request->student_id;
            $studentcourse->course_id = $request->course_id;;
            $studentcourse->save();
            return response()->json($studentcourse);
        } else {
            return response("Update unsuccessful, no such StudentCourse exists");
        }
    }

    function deleteStudentCourse($id)
    {
        try {
            $studentcourse = StudentCourse::findorfail($id);
            if ($studentcourse) {
                $deletedStudentCourse = $studentcourse;

                $studentcourse->delete();
                return response()->json($deletedStudentCourse);
            } else {
                return response("Delete unsuccessful, no such StudentCourse exits");
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'StudentCourse not found!'
            ], 404);
        }
    }


    function searchStudentCourse($enrollment_date)
    {
        try {
            $studentcourse = StudentCourse::where('enrollment_date', 'like', '%' . $enrollment_date . '%')->get();
            if ($studentcourse) {

                return response()->json($studentcourse);
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
