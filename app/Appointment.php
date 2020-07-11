<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = "appointment";

    protected $fillable = [
        'id', 'patientId', 'nutritionistId', 'weight','attachments', 'muscleMass', 'fatMass', 'totalWater', 'visceralFat', 'hipWaistRatio', 'date'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
