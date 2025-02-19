<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpensePlanTrans;
use App\Models\ExpensePlan;
use App\Models\Transaction;

class ExpensePlanTransController extends Controller
{
    //
    /**
     * Summary of create
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse     * 
     */
    public function create(Request $request)
    {
        $request->validate([
            'expense_plan_id' => 'required',
            'transaction_id' => 'required',
        ]);

        $user_id = auth()->user()->id;

        $expense_plan = ExpensePlan::find($request->expense_plan_id);

        if (!$expense_plan) {
            return response()->json([
                'message' => 'Expense plan not found!',
            ], 404);
        }

        $transaction = Transaction::find($request->transaction_id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found!',
            ], 404);
        }

        $expense = new ExpensePlanTrans();
        $expense->expense_plan_id = $request->expense_plan_id;
        $expense->transaction_id = $request->transaction_id;
        $expense->user_id = $user_id;


        $expense->save();

        return response()->json([
            'message' => 'Expense plan transaction created successfully!',
            'data' => $expense,
            'transaction' => $transaction,
            'expense_plan' => $expense_plan
        ], 201);

    }

    /**
     * Summary of delete
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse     * 
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $expense = ExpensePlanTrans::find($request->id);
        $user_id = auth()->user()->id;

        if (!$expense) {
            return response()->json([
                'message' => 'Expense plan transaction not found!',
            ], 404);
        }

        if ($expense->user_id != $user_id) {
            return response()->json([
                'message' => 'You are not authorized to delete this expense plan transaction!',
            ], 401);
        }

        $expense->delete();

        return response()->json([
            'message' => 'Expense plan transaction deleted successfully!',
        ], 200);
    }

    public function edit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'expense_plan_id' => 'required',
            'transaction_id' => 'required',
        ]);

        $user_id = auth()->user()->id;

        $expense = ExpensePlanTrans::find($request->id);

        if (!$expense) {
            return response()->json([
                'message' => 'Expense plan transaction not found!',
            ], 404);
        }

        if ($expense->user_id != $user_id) {
            return response()->json([
                'message' => 'You are not authorized to edit this expense plan transaction!',
            ], 401);
        }

        $expense_plan = ExpensePlan::find($request->expense_plan_id);

        if (!$expense_plan) {
            return response()->json([
                'message' => 'Expense plan not found!',
            ], 404);
        }

        $transaction = Transaction::find($request->transaction_id);

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found!',
            ], 404);
        }

        $expense->expense_plan_id = $request->expense_plan_id;
        $expense->transaction_id = $request->transaction_id;

        $expense->save();

        return response()->json([
            'message' => 'Expense plan transaction updated successfully!',
            'data' => $expense,
            'transaction' => $transaction,
            'expense_plan' => $expense_plan
        ], 200);
    }
}
