<?php

namespace Tests;

use App\Services\LeaderBoard\LeaderBoardService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
        resolve(LeaderBoardService::class)->clear();
    }
}
