<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpensePlan;

class ExpensePlanConroller extends Controller
{
    /**
     * Summary of create
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required',
        ]);

        $user_id = auth()->user()->id;

        $expensePlan = new ExpensePlan();
        $expensePlan->title = $request->title;
        $expensePlan->amount = $request->amount;
        $expensePlan->user_id = $user_id;

        if ($request->has('description')) {
            $expensePlan->description = $request->description;
        }

        $expensePlan->save();

        return response()->json([
            'message' => 'Successfully created expense plan!',
            'expensePlan' => $expensePlan
        ], 201);
    }


    /**
     * Summary of edit
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function edit(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'amount' => 'required',
        ]);

        $user_id = auth()->user()->id;

        $expensePlan = ExpensePlan::find($request->id);

        if (!$expensePlan) {
            return response()->json([
                'message' => 'Expense plan not found!'
            ], 404);
        }

        if ($expensePlan->user_id !== $user_id) {
            return response()->json([
                'message' => 'Unauthorized!'
            ], 401);
        }

        $expensePlan->title = $request->title;
        $expensePlan->amount = $request->amount;

        if ($request->has('description')) {
            $expensePlan->description = $request->description;
        }

        $expensePlan->save();

        return response()->json([
            'message' => 'Successfully updated expense plan!',
            'expensePlan' => $expensePlan
        ], 200);
    }




    /**
     * Summary of delete
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $user_id = auth()->user()->id;

        $expensePlan = ExpensePlan::find($request->id);

        if (!$expensePlan) {
            return response()->json([
                'message' => 'Expense plan not found!'
            ], 404);
        }

        if ($expensePlan->user_id !== $user_id) {
            return response()->json([
                'message' => 'Unauthorized!'
            ], 401);
        }

        $expensePlan->delete();

        return response()->json([
            'message' => 'Successfully deleted expense plan!'
        ], 200);
    }

    /**
     * Summary of getExpensePlan
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getExpensePlan()
    {
        $user_id = auth()->user()->id;

        $expensePlans = ExpensePlan::where('user_id', $user_id)->get();

        return response()->json([
            'expensePlans' => $expensePlans
        ], 200);
    }
}
