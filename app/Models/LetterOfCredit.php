<?php

namespace App\Models;

use Database\Factories\LetterOfCreditFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static where(string $string, mixed $param)
 */
class LetterOfCredit extends Model
{
    /** @use HasFactory<LetterOfCreditFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'bank_account_id',
        'lc_number',
        'issue_date',
        'expiry_date',
        'amount',
        'currency',
        'status',
        'description',
        'beneficiary_name',
        'beneficiary_address',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
