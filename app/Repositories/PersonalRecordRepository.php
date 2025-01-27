<?php

namespace App\Repositories;

use App\Models\PersonalRecord;
use Illuminate\Support\Facades\DB;

class PersonalRecordRepository {

    public function getRecordsByMovement(?int $movementId){
        $query = PersonalRecord::with(['user', 'movement'])
            ->select('user_id', 'movement_id', DB::raw('MAX(value) as record_value'))
            ->groupBy('user_id', 'movement_id');

        if ($movementId) {
            $query->where('movement_id', $movementId);
        }

        return $query->get();
    }
}
