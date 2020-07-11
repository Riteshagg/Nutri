<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $table = "users_to_trainer";
    protected $primaryKey = 'userId';

    protected $fillable = [
        'userId', 'trainerId', 'updated_at', 'created_at'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'userId');
    }

    public function trainerDetails()
    {
        return $this->belongsTo('App\User', 'trainerId');
    }
}
