<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InitialPlan extends Model
{
    protected $table = "initial_training_plan";

    protected $fillable = [
        'id', 'patientId', 'trainerId', 'planData','observations', 'created_at','updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

