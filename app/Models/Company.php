<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find($param)
 */
class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    protected $fillable = [
        'arabic_name',
        'english_name',
        'slogan',
        'email',
        'phone',
        'address',
        'website',
        'logo',
        'letterhead',
        'ceo_name',
        'employees',
    ];

    protected $casts = [
        'employees' => 'array',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    public function letterOfCredits(): HasMany
    {
        return $this->hasMany(LetterOfCredit::class);
    }

    public function outGoing(): HasMany
    {
        return $this->hasMany(OutGoing::class);
    }

    public function inComings(): HasMany
    {
        return $this->hasMany(InComing::class);
    }
}
