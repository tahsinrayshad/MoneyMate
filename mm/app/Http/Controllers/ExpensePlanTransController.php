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


    /**
     * Summary of edit
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse     * 
     */
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


    /**
     * Summary of getAll
     * @return mixed|\Illuminate\Http\JsonResponse     * 
     */
    public function getAll()
    {
        $user_id = auth()->user()->id;

        $expenses = ExpensePlanTrans::where('user_id', $user_id)->get();

        if (!$expenses) {
            return response()->json([
                'message' => 'No expense plan transactions found!',
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetched all expense plan transactions!',
            'data' => $expenses,
        ], 200);
    }


    /**
     * Summary of getSingleExpensePlanTrans
     * @param mixed $id 
     * @return mixed|\Illuminate\Http\JsonResponse     * 
     */
    public function getSingleExpensePlanTrans($id)
    {
        $user_id = auth()->user()->id;

        $expense = ExpensePlanTrans::where('user_id', $user_id)->where('id', $id)->first();

        if (!$expense) {
            return response()->json([
                'message' => 'Expense plan transaction not found!',
            ], 404);
        }

        return response()->json([
            'message' => 'Successfully fetched expense plan transaction!',
            'data' => $expense,
        ], 200);
    }
}
