<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnlineAppointment extends Model
{
    protected $table = "online_appointment";
    public $timestamps = false;

    protected $fillable = [
        'date', 'objectives', 'clinic_history', 'antropometrics','attachments', 'observations', 'food_diary', 'id', 'patientId', 'nutritionistId'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
