<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionTag;
use App\Models\Budget;
use App\Models\User;


class TransactionController extends Controller
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
            'transaction_tag_id' => 'required',
            'type' => 'required',
            'amount' => 'required',
        ]);

        $user_id = auth()->user()->id;

        $tag = TransactionTag::find($request->transaction_tag_id);

        if (!$tag) {
            return response()->json([
                'message' => 'Transaction tag not found!',
            ], 404);
        }

        $transaction = new Transaction();
        $transaction->transaction_tag_id = $request->transaction_tag_id;
        $transaction->type = $request->type;
        $transaction->amount = $request->amount;
        $transaction->user_id = $user_id;

        if ($request->has('description')) {
            $transaction->description = $request->description;
        }

        $budget = null;

        if($request->has('budget_id')){
            $budget = Budget::find($request->budget_id);
            if(!$budget){
                return response()->json([
                    'message' => 'Budget not found!',
                ], 404);
            }
            $transaction->budget_id = $request->budget_id;
        }

        $to_user = null;

        if($request->has('to_user_id')){
            $to_user = User::find($request->to_user_id);
            if(!$to_user){
                return response()->json([
                    'message' => 'User not found!',
                ], 404);
            }
            $transaction->to_user_id = $request->to_user_id;
        }

        $transaction->save();

        

        return response()->json([
            'message' => 'Successfully created transaction!',
            'transaction' => $transaction,
            'tag' => $tag,
            'budget' => $budget,
            'to_user' => $to_user,
        ], 201);        
    }


    /**
     * Summary of delete
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse 
     */
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $transaction = Transaction::find($request->id);
        $user_id = auth()->user()->id;

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found!',
            ], 404);
        }

        if ($transaction->user_id != $user_id) {
            return response()->json([
                'message' => 'Unauthorized!',
            ], 401);
        }

        $transaction->delete();

        return response()->json([
            'message' => 'Successfully deleted transaction!',
        ], 200);
    }
    

    /**
     * Summary of getAll
     * @return mixed|\Illuminate\Http\JsonResponse 
     */
    public function getAll()
    {
        $user_id = auth()->user()->id;

        $transactions = Transaction::where('user_id', $user_id)->get();

        return response()->json([
            'transactions' => $transactions,
        ], 200);
    }

    /**
     * Summary of getSingleTransaction
     * @param mixed $id 
     * @return mixed|\Illuminate\Http\JsonResponse 
     */
    public function getSingleTransaction($id)
    {
        $user_id = auth()->user()->id;

        $transaction = Transaction::where('user_id', $user_id)->where('id', $id)->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found!',
            ], 404);
        }

        $budget = null;
        if ($transaction->budget_id) {
            $budget = Budget::select('id', 'title', 'amount', 'description')->find($transaction->budget_id);
        }

        $to_user = null;
        if ($transaction->to_user_id) {
            $to_user = User::select('id', 'username', 'email')->find($transaction->to_user_id);
        }

        return response()->json([
            'transaction' => $transaction,
            'budget' => $budget,
            'to_user' => $to_user,
        ], 200);
    }

    /**
     * Summary of getTransactionsByDay
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse 
     */
    public function getTransactionsByDay(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $user_id = auth()->user()->id;
        $date = $request->date;

        $transactions = Transaction::where('user_id', $user_id)
            ->whereDate('created_at', $date)
            ->get();

        $totalTransactions = $transactions->count();

        $totalExpense = $transactions->filter(function ($transaction) {
            return in_array($transaction->type, ['expense', 'loan']);
        })->sum('amount');

        $totalIncome = $transactions->filter(function ($transaction) {
            return in_array($transaction->type, ['income', 'borrow']);
        })->sum('amount');

        return response()->json([
            'total_transactions' => $totalTransactions,
            'total_expense' => $totalExpense,
            'total_income' => $totalIncome,
            'transactions' => $transactions,            
        ], 200);
    }

    /**
     * Summary of getTransactionsByMonth
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function getTransactionsByMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $user_id = auth()->user()->id;
        $month = $request->month;

        $transactions = Transaction::where('user_id', $user_id)
            ->whereYear('created_at', '=', date('Y', strtotime($month)))
            ->whereMonth('created_at', '=', date('m', strtotime($month)))
            ->get();

            $totalTransactions = $transactions->count();

            $totalExpense = $transactions->filter(function ($transaction) {
                return in_array($transaction->type, ['expense', 'loan']);
            })->sum('amount');
    
            $totalIncome = $transactions->filter(function ($transaction) {
                return in_array($transaction->type, ['income', 'borrow']);
            })->sum('amount');


        return response()->json([
            'total_transactions' => $totalTransactions,
            'total_expense' => $totalExpense,
            'total_income' => $totalIncome,
            'transactions' => $transactions,
        ], 200);
    }

    /**
     * Summary of getTransactionsByYear
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */

    public function getTransactionsByYear(Request $request)
    {
        $request->validate([
            'year' => 'required|date_format:Y',
        ]);

        $user_id = auth()->user()->id;
        $year = $request->year;

        $transactions = Transaction::where('user_id', $user_id)
            ->whereYear('created_at', '=', $year)
            ->get();

            $totalTransactions = $transactions->count();

            $totalExpense = $transactions->filter(function ($transaction) {
                return in_array($transaction->type, ['expense', 'loan']);
            })->sum('amount');
    
            $totalIncome = $transactions->filter(function ($transaction) {
                return in_array($transaction->type, ['income', 'borrow']);
            })->sum('amount');

        return response()->json([
            'total_transactions' => $totalTransactions,
            'total_expense' => $totalExpense,
            'total_income' => $totalIncome,
            'transactions' => $transactions,
        ], 200);
    }

    public function getStatistics(){
        $user_id = auth()->user()->id;

        $transactions = Transaction::where('user_id', $user_id)->get();

        $totalTransactions = $transactions->count();

        $totalExpense = $transactions->filter(function ($transaction) {
            return in_array($transaction->type, ['expense']);
        })->sum('amount');

        $totalIncome = $transactions->filter(function ($transaction) {
            return in_array($transaction->type, ['income']);
        })->sum('amount');

        $totalLoan = $transactions->filter(function ($transaction) {
            return in_array($transaction->type, ['loan']);
        })->sum('amount');

        $totalBorrow = $transactions->filter(function ($transaction) {
            return in_array($transaction->type, ['borrow']);
        })->sum('amount');

        $curr_balance = $totalIncome - $totalExpense + $totalBorrow - $totalLoan;

        return response()->json([
            'total_transactions' => $totalTransactions,
            'total_expense' => $totalExpense,
            'total_income' => $totalIncome,
            'total_loan' => $totalLoan,
            'total_borrow' => $totalBorrow,
            'current_balance' => $curr_balance,
        ], 200);
    }



}
