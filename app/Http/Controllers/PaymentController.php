<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // $payments = Payment::all();
        $payments = Payment::join('students', 'payments.student_id', '=', 'students.id')
            ->select('payments.*', 'students.student_name as student_name')
            ->get();
        if ($payments) {
            return response()->json([
                "Payments" => $payments
            ], 200);
        } else {
            return response()->json([
                "Error" => "No payment record was found."
            ], 404);
        }
    }

    public function createPayment(Request $request)
    {
        $request->validate([
            'payment_date' => 'required|string|max:255',
            'amount_paid' => 'required|numeric|max:25500',
            'receipt_number' => 'required|string|max:255',
            'student_id' => 'required|integer|exists:students,id',
        ]);

        $payment = new Payment;
        $payment->payment_date = $request->payment_date;
        $payment->amount_paid = $request->amount_paid;
        $payment->receipt_number = $request->receipt_number;
        $payment->student_id = $request->student_id;

        $result = $payment->save();
        if ($result) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function getPayment($id)
    {
        try {
            $payment = Payment::findorfail($id)
                ->join('students', 'payments.student_id', '=', 'students.id')
                ->select('payments.*', 'students.student_name as student_name')
                ->findorfail($id);
            return response()->json($payment);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Payment not found'
            ], 404);
        }
    }

    public function updatePayment(Request $request, $id)
    {
        // \Log::info('Request Data -2 : ' . json_encode($request->all()));
        $request->validate([
            'payment_date' => 'required|string|max:255',
            'amount_paid' => 'required|numeric|max:25500',
            'receipt_number' => 'required|string|max:255',
            'student_id' => 'required|integer|exists:students,id',
        ]);

        $payment = Payment::find($id);

        if ($payment) {
            $payment->payment_date = $request->payment_date;
            $payment->amount_paid = $request->amount_paid;
            $payment->receipt_number = $request->receipt_number;
            $payment->student_id = $request->student_id;
            $payment->save();
            return response()->json($payment);
        } else {
            return response("Update unsuccessful, no such Payment exists");
        }
    }

    function deletePayment($id)
    {
        try {
            $payment = Payment::findorfail($id);
            if ($payment) {
                $deletedPayment = $payment;

                $payment->delete();
                return response()->json($deletedPayment);
            } else {
                return response("Delete unsuccessful, no such Payment exits");
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Payment not found!'
            ], 404);
        }
    }


    function searchPayment($payment_date)
    {
        try {
            $payment = Payment::where('payment_date', 'like', '%' . $payment_date . '%')->get();
            if ($payment) {

                return response()->json($payment);
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
