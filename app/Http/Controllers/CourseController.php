<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        // $courses = Course::all();
        $courses = Course::join('madrassas', 'courses.madrassa_id', '=', 'madrassas.id')
            ->select('courses.*', 'madrassas.name as madrassa_name')
            ->get();
        if ($courses) {
            return response()->json([
                "Courses" => $courses
            ], 200);
        } else {
            return response()->json([
                "Error" => "No course record was found."
            ], 404);
        }
    }

    public function createCourse(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'charges' => 'required|numeric|max:25500',
            'madrassa_id' => 'required|integer|exists:madrassas,id'
        ]);

        $course = new Course;
        $course->course_name = $request->course_name;
        $course->description = $request->description;
        $course->charges = $request->charges;
        $course->madrassa_id = $request->madrassa_id;

        $result = $course->save();
        if ($result) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getCourse($id)
    {
        try {
            $course = Course::findorfail($id)
                ->join('madrassas', 'courses.madrassa_id', '=', 'madrassas.id')
                ->select('courses.*', 'madrassas.name as madrassa_name')
                ->findorfail($id);
            return response()->json($course);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Course not found'
            ], 404);
        }
    }

    public function updateCourse(Request $request, $id)
    {
        // \Log::info('Request Data -2 : ' . json_encode($request->all()));
        $request->validate([
            'course_name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'charges' => 'required|numeric|max:25500',
            'madrassa_id' => 'required|integer|exists:madrassas,id'
        ]);

        $course = Course::find($id);

        if ($course) {
            $course->course_name = $request->course_name;
            $course->description = $request->description;
            $course->charges = $request->charges;
            $course->madrassa_id = $request->madrassa_id;
            $course->save();
            return response()->json($course);
        } else {
            return response("Update unsuccessful, no such Course exists");
        }
    }

    function deleteCourse($id)
    {
        try {
            $course = Course::findorfail($id);
            if ($course) {
                $deletedCourse = $course;

                $course->delete();
                return response()->json($deletedCourse);
            } else {
                return response("Delete unsuccessful, no such Course exits");
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Course not found!'
            ], 404);
        }
    }


    function searchCourse($name)
    {
        try {
            $course = Course::where('course_name', 'like', '%' . $name . '%')->get();
            if ($course) {

                return response()->json($course);
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
