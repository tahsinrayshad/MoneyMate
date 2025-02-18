<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactionTag()
    {
        return $this->belongsTo(TransactionTag::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function expensePlanTransaction()
    {
        return $this->hasMany(ExpensePlanTrans::class);
    }
}
