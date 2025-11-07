<?php

namespace Tests\Unit\Services\LeaderBoard;

use App\Services\LeaderBoard\LeaderBoardService;
use App\Services\LeaderBoard\RedisLeaderBoard;
use Tests\TestCase;

// use PHPUnit\Framework\TestCase;

class RedisLeaderBoardTest extends TestCase
{
    private null|RedisLeaderBoard $sut = null;

    public function setUp(): void
    {
        $this->sut = new RedisLeaderBoard;

        parent::setUp();
    }

    public function test_update_and_get_score()
    {
        // setup
        $score = floatval(50);

        // action
        $this->sut->update('unique_key', $score);
        $expected = $this->sut->getScore('unique_key');
        // assert
        $this->assertSame($expected, $score);
    }

    public function test_get_rank()
    {
        // setup
        $this->sut->update('rank 1', floatval(50));
        $this->sut->update('rank 2', floatval(49));

        // act and assert
        $this->assertSame($this->sut->getRank('rank 1'), 1);
        $this->assertSame($this->sut->getRank('rank 2'), 2);
    }

    public function test_get_top_n()
    {
        // setup
        $this->sut->update('rank 1', $rank1Score = floatval(50));
        $this->sut->update('rank 2', $rank2Score = floatval(49));

        // act
        $topN = $this->sut->getTopN(2);
        
        // assert
        $this->assertSame($topN['rank 1'], $rank1Score);
        $this->assertSame($topN['rank 2'], $rank2Score);
    }

    public function test_clear()
    {
        // act
        $this->sut->update('unique_key', $rank1Score = floatval(50));

        // act
        $this->sut->clear();
        
        // assert
        $this->assertEmpty($this->sut->getTopN(2));
    }
}
