<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\LeaderBoard\LeaderBoardService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateUserScoreJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $userId, private int $score)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(LeaderBoardService $leaderBoardService): void
    {
        $user = User::find($this->userId);

        $score = $leaderBoardService->getScore($user->getLeaderBoardKey());

        if ($score) {
            $user->score = $score;
            $user->save();
        }
    }
}
