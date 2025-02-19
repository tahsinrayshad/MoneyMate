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

        return response()->json([
            'transaction' => $transaction,
        ], 200);
    }
}
