<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensePlanTrans extends Model
{
    use HasFactory;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function expensePlan()
    {
        return $this->belongsTo(ExpensePlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
