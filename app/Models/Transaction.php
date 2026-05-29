<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = ['invoice_no', 'total_price', 'cash_received', 'cash_change'];

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}