<?php

namespace App\Models;

use App\Enums\Game;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    private const STARTING_SCORE = 0;

    public const FIELDS = [
        'id',
        'name',
        'email',
        'score',
        'created_at',
        'updated_at',
        'password',
    ];

    public static function generate(array $data): static
    {
        $user = new static();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->score = static::STARTING_SCORE;
        $user->save();
        
        return $user;
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getRank()
    {
        return Redis::zrevrank(Game::LEADERBOARD, $this->getLeaderBoardKey());
    }

    public function getLeaderBoardKey()
    {
        return $this->name;
    }

    public function password():Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::needsRehash($value) ? Hash::make($value) : $value
        );
    }

}
