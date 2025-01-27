<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\RankingService;
use App\Repositories\PersonalRecordRepository;
use App\Models\PersonalRecord;
use Mockery;

class RankingServiceTest extends TestCase {
    
    public function test_generate_ranking() {
        $mockRepo = Mockery::mock(PersonalRecordRepository::class);
        
        // Criando instâncias simuladas de PersonalRecord
        $mockRecords = collect([
            new PersonalRecord([
                'user_name' => 'Jose',
                'record_value' => 190,
                'record_date' => '2021-01-06'
            ]),
            new PersonalRecord([
                'user_name' => 'Paulo',
                'record_value' => 170,
                'record_date' => '2021-01-01'
            ]),
            new PersonalRecord([
                'user_name' => 'Joao',
                'record_value' => 180,
                'record_date' => '2021-01-02'
            ]),
        ]);
    
        $mockRepo->shouldReceive('getRecordsByMovement')
            ->andReturn($mockRecords);
    
        $service = new RankingService($mockRepo);
        $ranking = $service->getRanking(1);
    
        // Verifica se o ranking retornado tem 3 usuários
        $this->assertCount(3, $ranking['ranking']);
    
        // Verifica se o primeiro do ranking é o "Jose"
        $this->assertEquals('Jose', $ranking['ranking'][0]['user_name']);
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
