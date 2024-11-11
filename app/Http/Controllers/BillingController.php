<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        // $billings = Billing::all();
        $billings = Billing::join('student_courses', 'billings.student_course_id', '=', 'student_courses.id')
            ->join('students', 'student_courses.student_id', '=', 'students.id')
            ->join('courses', 'student_courses.course_id', '=', 'courses.id')
            ->select('billings.*', 'students.student_name as student_name', 'courses.course_name as course_name')
            ->get();
        if ($billings) {
            return response()->json([
                "Billings" => $billings
            ], 200);
        } else {
            return response()->json([
                "Error" => "No billing record was found."
            ], 404);
        }
    }

    public function createBilling(Request $request)
    {
        $request->validate([
            'billing_date' => 'required|string|max:255',
            'amount_charged' => 'required|numeric|max:25500',
            'invoice_number' => 'required|string|max:255',
            'student_course_id' => 'required|integer|exists:student_courses,id'
        ]);

        $billing = new Billing;
        $billing->billing_date = $request->billing_date;
        $billing->amount_charged = $request->amount_charged;
        $billing->invoice_number = $request->invoice_number;
        $billing->student_course_id = $request->student_course_id;

        $result = $billing->save();
        if ($result) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getBilling($id)
    {
        try {
            $billing = Billing::findorfail($id)
                ->join('student_courses', 'billings.student_course_id', '=', 'student_courses.id')
                ->join('students', 'student_courses.student_id', '=', 'students.id')
                ->join('courses', 'student_courses.course_id', '=', 'courses.id')
                ->select('billings.*', 'students.student_name as student_name', 'courses.course_name as course_name')
                ->findorfail($id);
            return response()->json($billing);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Billing not found'
            ], 404);
        }
    }

    public function updateBilling(Request $request, $id)
    {
        // \Log::info('Request Data -2 : ' . json_encode($request->all()));
        $request->validate([
            'billing_date' => 'required|string|max:255',
            'amount_charged' => 'required|numeric|max:25500',
            'invoice_number' => 'required|string|max:255',
            'student_course_id' => 'required|integer|exists:student_courses,id'
        ]);

        $billing = Billing::find($id);

        if ($billing) {
            $billing->billing_date = $request->billing_date;
            $billing->amount_charged = $request->amount_charged;
            $billing->invoice_number = $request->invoice_number;
            $billing->student_course_id = $request->student_course_id;
            $billing->save();
            return response()->json($billing);
        } else {
            return response("Update unsuccessful, no such Billing exists");
        }
    }

    function deleteBilling($id)
    {
        try {
            $billing = Billing::findorfail($id);
            if ($billing) {
                $deletedBilling = $billing;

                $billing->delete();
                return response()->json($deletedBilling);
            } else {
                return response("Delete unsuccessful, no such Billing exits");
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Billing not found!'
            ], 404);
        }
    }


    function searchBilling($billing_date)
    {
        try {
            $billing = Billing::where('billing_date', 'like', '%' . $billing_date . '%')->get();
            if ($billing) {

                return response()->json($billing);
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
