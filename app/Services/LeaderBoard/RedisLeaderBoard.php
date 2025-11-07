<?php


namespace App\Services\LeaderBoard;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Enums\Game;
use Exception;

class RedisLeaderBoard implements LeaderBoardService
{
    static private $update_failed_message = "Filed updating score";

    static private $get_score_failed_message = "Filed reading score";

    static private $get_rank_failed_message = "Filed reading rank";

    public function update(string $key, int $value): bool
    {
        try {
            Redis::zadd(Game::LEADERBOARD, $value, $key);
            return true;
        } catch (Exception $e) {
            $this->logException($e, ['key' => $key, 'value' => $value], static::$update_failed_message);
            return false;
        }
    }

    public function getScore(string $key): float|null
    {
        try {
            $score = Redis::zscore(Game::LEADERBOARD, $key);
            return is_float($score) ? $score : null;
        } catch (Exception $e) {
            $this->logException($e, ['key' => $key], static::$get_score_failed_message);
            return null;
        }
    }

    public function getRank(string $key): int|null
    {
        try {
            $rank = Redis::zrevrank(Game::LEADERBOARD, $key);
            return is_integer($rank) ? ($rank + 1) : null;
        } catch (Exception $e) {
            $this->logException($e, ['key' => $key], static::$get_rank_failed_message);
            return null;
        }
    }

    private function logException(Exception $e, array $context, string $message): void
    {
        Log::channel(Game::LEADERBOARD)
            ->info(
                $message,
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    ...$context
                ]
            );
    }
}
