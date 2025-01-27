<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\RankingService;
use App\Repositories\PersonalRecordRepository;
use App\Models\PersonalRecord;
use App\Models\UsersRecord;
use App\Models\Movement;

use Mockery;
use Illuminate\Database\Eloquent\Collection;


class RankingServiceTest extends TestCase {
    
    public function test_generate_ranking(){
        $movementId = 1;

        $records = \App\Models\PersonalRecord::with(['user', 'movement'])
            ->where('movement_id', $movementId)
            ->get();

        $rankingService = new \App\Services\RankingService(new \App\Repositories\PersonalRecordRepository());
        $ranking = $rankingService->generateRanking($records, $movementId);

        $this->assertEquals('Deadlift', $ranking['movement']);
    }
    
    public function test_generate_ranking_with_no_records() {
        $mockRepo = Mockery::mock(PersonalRecordRepository::class);
    
        // Simula um retorno vazio
        $mockRepo->shouldReceive('getRecordsByMovement')
            ->andReturn(collect([]));
    
        $service = new RankingService($mockRepo);
        $ranking = $service->getRanking(1);
    
        // Verifica se o ranking retornado está vazio
        $this->assertEquals([], $ranking['ranking']);
    
        // Verifica se o movimento foi tratado corretamente
        $this->assertEquals('Movimento não encontrado', $ranking['movement']);
    }
}
