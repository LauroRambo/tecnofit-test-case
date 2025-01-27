<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRecord extends Model {
    protected $table = 'users_record';

    protected $fillable = ['name'];

    public function personalRecords() {
        return $this->hasMany(PersonalRecord::class);
    }
}
