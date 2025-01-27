<?php

namespace App\Services;

use App\Repositories\PersonalRecordRepository;

class RankingService {
    private PersonalRecordRepository $repository;

    public function __construct(PersonalRecordRepository $repository) {
        $this->repository = $repository;
    }

    public function getRanking($movementId): array {
        $records = $this->repository->getRecordsByMovement($movementId);

        if ($records instanceof \Illuminate\Support\Collection && $records->isEmpty()) {
            return [
                'movement' => $movementId ? 'Movimento não encontrado' : 'Geral',
                'ranking' => []
            ];
        }

        return $this->generateRanking($records, $movementId);
    }

    public function generateRanking(\Illuminate\Database\Eloquent\Collection $records, $movementId): array {
        $records = $records->sortByDesc('record_value')->values();  

        $ranking = [];
        $currentPosition = 0;
        $prevValue = null;

        foreach ($records as $index => $record) {
            if ($record->record_value !== $prevValue) {
                $currentPosition++;
            }

            $ranking[] = [
                'user_name' => $record->user->name,
                'record_value' => $record->record_value,
                'position' => $currentPosition,
                'date' => $record->updated_at,
                'movement_name' => $record->movement->name,
            ];

            $prevValue = $record->record_value;
        }

        return [
            'movement' => $movementId ? $records->first()->movement->name : 'Geral', 
            'ranking' => $ranking
        ];
    }
}
