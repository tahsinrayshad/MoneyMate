<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionTag;

class TransactionTagController extends Controller
{
    //
    public function getAll()
    {
        $transactionTags = TransactionTag::all();
        return response()->json(
            $transactionTags
        , 200);
    }
}
