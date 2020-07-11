<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nutritionist extends Model
{
    protected $table = "users_to_nutritionist";
    protected $primaryKey = 'userId';

    protected $fillable = [
        'userId', 'nutritionistId', 'updated_at', 'created_at'
    ];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'userId');
    }

    public function nutritionistDetails()
    {
        return $this->belongsTo('App\User', 'nutritionistId');
    }
}
