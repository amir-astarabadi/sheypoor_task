<?php


namespace App\Services\LeaderBoard;


interface LeaderBoardService
{
    public function update(string $key, int $value): bool;

    public function getScore(string $key): float|null;

    public function getRank(string $key): int|null;

    public function getTopN(int $n): array;

    public function clear();
}
