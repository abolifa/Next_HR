<?php

namespace App\Models;

use Database\Factories\OutGoingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static latest()
 * @method static findOrFail($record)
 */
class OutGoing extends Model
{
    /** @use HasFactory<OutGoingFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'subject',
        'title',
        'receiver',
        'body',
        'attachments',
        'date',
        'number',
        'ceo',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
