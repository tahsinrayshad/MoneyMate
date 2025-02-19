<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;

class BudgetController extends Controller
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
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $user_id = auth()->user()->id;

        $budget = new Budget();
        $budget->title = $request->title;
        $budget->amount = $request->amount;
        $budget->user_id = $user_id;
        $start_date = \DateTime::createFromFormat('Y-m-d', $request->start_date);
        $end_date = \DateTime::createFromFormat('Y-m-d', $request->end_date);

        if ($start_date && $start_date->format('Y-m-d') === $request->start_date) {
            $budget->start_date = $request->start_date;
        } else {
            $budget->start_date = date('Y-m-d', strtotime($request->start_date));
        }

        if ($end_date && $end_date->format('Y-m-d') === $request->end_date) {
            $budget->end_date = $request->end_date;
        } else {
            $budget->end_date = date('Y-m-d', strtotime($request->end_date));
        }

        if ($request->has('description')) {
            $budget->description = $request->description;
        }

        $budget->save();

        return response()->json([
            'message' => 'Successfully created budget!',
            'budget' => $budget
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
            'title' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'id' => 'required'
        ]);

        $user_id = auth()->user()->id;

        $id = $request->id;

        $budget = Budget::find($id);

        if (!$budget) {
            return response()->json([
                'message' => 'Budget not found!',
            ], 404);
        }

        if ($budget->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'Unauthorized action!',
            ], 401);
        }

        $budget->title = $request->title;
        $budget->amount = $request->amount;
        $budget->user_id = $user_id;
        $start_date = \DateTime::createFromFormat('Y-m-d', $request->start_date);
        $end_date = \DateTime::createFromFormat('Y-m-d', $request->end_date);

        if ($start_date && $start_date->format('Y-m-d') === $request->start_date) {
            $budget->start_date = $request->start_date;
        } else {
            $budget->start_date = date('Y-m-d', strtotime($request->start_date));
        }

        if ($end_date && $end_date->format('Y-m-d') === $request->end_date) {
            $budget->end_date = $request->end_date;
        } else {
            $budget->end_date = date('Y-m-d', strtotime($request->end_date));
        }

        if ($request->has('description')) {
            $budget->description = $request->description;
        }

        $budget->save();

        return response()->json([
            'message' => 'Successfully updated budget!',
            'budget' => $budget
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

        $id = $request->id;

        $budget = Budget::find($id);

        if (!$budget) {
            return response()->json([
                'message' => 'Budget not found!',
            ], 404);
        }

        if ($budget->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'Unauthorized action!',
            ], 401);
        }

        $budget->delete();

        return response()->json([
            'message' => 'Successfully deleted budget!',
        ], 200);
    }

    /**
     * Summary of getAll
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getAllOfAUser()
    {
        $user_id = auth()->user()->id;

        $budgets = Budget::where('user_id', $user_id)->get();

        return response()->json([
            'budgets' => $budgets
        ], 200);
    }

    /**
     * Summary of getSingleBudget
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function getSingleBudget($id)
    {
        $budget = Budget::find($id);

        if (!$budget) {
            return response()->json([
                'message' => 'Budget not found!',
            ], 404);
        }

        if ($budget->user_id != auth()->user()->id) {
            return response()->json([
                'message' => 'Unauthorized action!',
            ], 401);
        }

        return response()->json([
            'budget' => $budget
        ], 200);
    }
}
