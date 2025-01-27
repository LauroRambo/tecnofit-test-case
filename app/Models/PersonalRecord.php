<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalRecord extends Model {
    protected $fillable = ['user_id', 'movement_id', 'value', 'date'];

    public function user() {
        return $this->belongsTo(UsersRecord::class);
    }

    public function movement() {
        return $this->belongsTo(Movement::class);
    }
}
