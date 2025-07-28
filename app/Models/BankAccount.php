<?php

namespace App\Models;

use Database\Factories\BankAccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    /** @use HasFactory<BankAccountFactory> */
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'branch_name',
        'account_number',
        'account_type',
        'currency',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
