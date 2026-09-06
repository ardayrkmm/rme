<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicalRecord extends Model
{
    use HasUuids, HasFactory, SoftDeletes, \App\Traits\LogsActivity;

    protected $fillable = [
        'visit_number',
        'patient_id',
        'service_id',
        'physiotherapist_id',
        'appointment_id',
        'examination_date',
        'anamnesis',
        'diagnosis',
        'therapy',
        'notes',
        'prescription',
        'attachment',
    ];

    protected $casts = [
        'examination_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function physiotherapist()
    {
        return $this->belongsTo(Physiotherapist::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function service()
    {
        return $this->belongsTo(ServiceMaster::class, 'service_id');
    }
}
