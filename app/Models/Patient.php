<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasUuids, HasFactory, SoftDeletes, \App\Traits\LogsActivity;

    protected $fillable = [
        'medical_record_number',
        'nik',
        'name',
        'birth_date',
        'patient_category_id',
        'gender_id',
        'blood_type',
        'address',
        'phone',
        'email',
        'occupation',
        'marital_status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_history',
        'allergies',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function category()
    {
        return $this->belongsTo(PatientCategory::class, 'patient_category_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }
}
