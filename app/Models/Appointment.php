<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasUuids, HasFactory, SoftDeletes, \App\Traits\LogsActivity;

    protected $fillable = [
        'visit_number',
        'patient_id',
        'physiotherapist_id',
        'service_master_id',
        'appointment_date',
        'appointment_time',
        'complaint',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function physiotherapist()
    {
        return $this->belongsTo(Physiotherapist::class);
    }

    public function serviceMaster()
    {
        return $this->belongsTo(ServiceMaster::class);
    }
}
