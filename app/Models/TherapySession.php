<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TherapySession extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'patient_id',
        'physiotherapist_id',
        'appointment_id',
        'service_master_id',
        'service_master_ids',
        'therapy_date',
        'complaint',
        'objective',
        'assessment',
        'plan',
        'treatment_given',
        'duration',
        'notes',
        'status',
    ];

    protected $casts = [
        'therapy_date' => 'date',
        'service_master_ids' => 'array',
        'duration' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function physiotherapist(): BelongsTo
    {
        return $this->belongsTo(Physiotherapist::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function serviceMaster(): BelongsTo
    {
        return $this->belongsTo(ServiceMaster::class, 'service_master_id');
    }
}
