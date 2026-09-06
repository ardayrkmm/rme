<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Physiotherapist extends Model
{
    use HasUuids, HasFactory, SoftDeletes, \App\Traits\LogsActivity;

    protected $fillable = [
        'name',
        'specialization',
        'sip',
        'phone',
        'email',
        'address',
        'gender',
        'photo',
        'status',
    ];

    /**
     * Get the physiotherapist's photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo) {
            return url('storage/' . $this->photo);
        }
        return null;
    }


    /**
     * Get the appointments associated with the physiotherapist.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the medical records handled by the physiotherapist.
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
