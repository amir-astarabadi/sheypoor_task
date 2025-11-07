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

    static private $get_top_n_failed_message = "Filed reading top n";
    
    static private $clear_failed_message = "Filed clear leaderboard";
    
    public function update(string $key, int $value): bool
    {
        try {
            Redis::zadd(Game::LEADERBOARD, $value, $key);
            return true;
        } catch (Exception $e) {
            $this->logException($e, static::$update_failed_message, ['key' => $key, 'value' => $value]);
            return false;
        }
    }

    public function getScore(string $key): float|null
    {
        try {
            $score = Redis::zscore(Game::LEADERBOARD, $key);
            return is_float($score) ? $score : null;
        } catch (Exception $e) {
            $this->logException($e, static::$get_score_failed_message, ['key' => $key]);
            return null;
        }
    }

    public function getRank(string $key): int|null
    {
        try {
            $rank = Redis::zrevrank(Game::LEADERBOARD, $key);
            return is_integer($rank) ? ($rank + 1) : null;
        } catch (Exception $e) {
            $this->logException($e, static::$get_rank_failed_message, ['key' => $key]);
            return null;
        }
    }

    public function getTopN(int $n, int $offset = 0): array
    {
        try {
            $topN = Redis::zrevrange(Game::LEADERBOARD, $offset, $n - 1, true);;
            return is_array($topN) ? $topN : [];
        } catch (Exception $e) {
            $this->logException($e, static::$get_top_n_failed_message, ['top_n' => $n, 'offset' => $offset]);
            return [];
        }
    }

    public function clear()
    {
        try {
            Redis::del(Game::LEADERBOARD);
        } catch (Exception $e) {
            $this->logException($e, static::$clear_failed_message);
        }
    }

    private function logException(Exception $e, string $message, array $context = []): void
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
