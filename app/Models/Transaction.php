<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_date',
        'total_amount',
        'created_by'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Define the relationship with the TransactionDetail model
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
