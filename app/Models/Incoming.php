<?php

namespace App\Models;

use Database\Factories\IncomingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static latest()
 * @method static pluck(string $string)
 * @method static findOrFail($record)
 */
class Incoming extends Model
{
    /** @use HasFactory<IncomingFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'subject',
        'from',
        'number',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
