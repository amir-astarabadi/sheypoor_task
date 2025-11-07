<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\LeaderBoard\LeaderBoardService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LeaderBoardReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:leader-board-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resrote leader board';

    /**
     * Execute the console command.
     */
    public function handle(LeaderBoardService $leaderBoardService)
    {

        Artisan::call('down');
        $this->info('update database...');
        $this->updateDatabaseFromLeaderBoard();
        $this->info('clear leader board...');
        $this->clearLeaderBoard();
        $this->info('refill leader board...');
        $this->refillLeaderBoardFromDatabase();
        Artisan::call('up');
    }

    private function updateDatabaseFromLeaderBoard()
    {
        $chunkSize = 1000;
        $leaderBoardService = resolve(LeaderBoardService::class);

        $all = $leaderBoardService->getTopN(-1);
        for ($i = 0; $i < count($all); $i += $chunkSize) {
            $chunk = array_slice($all, $i, $chunkSize);
            User::select(['id', 'name'])
                ->whereIn('name', array_keys($chunk))
                ->get()
                ->each(function ($user) use ($leaderBoardService, $chunk) {
                    $leaderBoardService->update($user->getLeaderBoardKey(), $chunk[$user->name]);
                });
        }
    }

    private function clearLeaderBoard()
    {
        resolve(LeaderBoardService::class)->clear();
    }

    private function refillLeaderBoardFromDatabase()
    {
        $leaderBoardService = resolve(LeaderBoardService::class);

        User::select(['id', 'name', 'score'])->chunkById(1000, function($chunk)use($leaderBoardService){
            foreach($chunk as $user){
                $leaderBoardService->update($user->getLeaderBoardKey(), $user->getRawOriginal('score'));
            }
        });
    }
}
