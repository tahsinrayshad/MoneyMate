<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensePlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expensePlanTransactions()
    {
        return $this->hasMany(ExpensePlanTrans::class);
    }
}
