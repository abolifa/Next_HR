<?php

namespace App\Models;

use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'type',
        'model',
        'plate_number',
        'chassis_number',
        'registration_number',
        'color',
        'acquisition_date',
        'insurance_expiry_date',
        'technical_inspection_due',
        'mileage',
        'status',
        'attachments',
        'assigned_to_employee_id',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to_employee_id');
    }
}
