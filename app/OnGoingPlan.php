<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OnGoingPlan extends Model
{
    protected $table = "ongoing_training_plan";

    protected $fillable = [
        'id','date','objectives','anthropometricData','motivation','espacoTemporal','alteracaoAoTreino','adaptacoes','attachment', 'patientId','trainingPlan', 'trainerId', 'created_at','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
