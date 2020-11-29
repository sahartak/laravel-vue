<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

/**
 * class User
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property float $balance
 * @property int $max_amount
 * @property int $total_bid
 *
 * @property ItemBid[] $bids
 */
class User
{
    use Notifiable;

    const DEFAULT_MAX_BID = 500;

    const DEFAULT_USERS = [
        1 => [
            'id' => 1,
            'name' => 'User1',
            'email' => 'user1@gmail.com',
            'password' => '$2y$10$7ci5zmNcRb4iVHYWd.occOzIbwiXQT7W0iC2WnAme1B/pIC6fXEZG',
        ],
        2 => [
            'id' => 2,
            'name' => 'User2',
            'email' => 'user2@gmail.com',
            'password' => '$2y$10$RF5jXmgPucCG2D9oKrhqnOB7YGQFmK8MOL8an9iTAhyX9Z6g9UFzu',
        ],
        3 => [
            'id' => 3,
            'name' => 'User3',
            'email' => 'user3@gmail.com',
            'password' => '$2y$10$7ci5zmNcRb4iVHYWd.occOzIbwiXQT7W0iC2WnAme1B/pIC6fXEZG',
        ],
    ];

    public static function getUserByEmail(string $email): ?self
    {
        foreach (self::DEFAULT_USERS as $userData) {
            if ($email == $userData['email']) {
                $user = new self();
                $user->loadModel($userData);
                return $user;
            }
        }
        return null;
    }

    public static function getUserById(int $id): ?User
    {
        $userData = static::DEFAULT_USERS[$id] ?? null;
        if ($userData) {
            $user = new self();
            $user->loadModel($userData);
            return $user;
        }
        return null;
    }

    public function validatePassword(string $password)
    {
        return Hash::check($password, $this->password);
    }

    public function loginUser()
    {
        $token = Str::random(64);
        Cache::put($token, $this->id, config('app.authExpireTime'));
        return $token;

    }

    public static function getByAccessToken(string $token): ?self
    {
        $userId = Cache::get($token);
        if ($userId) {
            foreach (self::DEFAULT_USERS as $userData) {
                if ($userId == $userData['id']) {
                    $user = new self();
                    $user->loadModel($userData);
                    return $user;
                }
            }
        }
        return null;
    }

    public function settings(): ?UserSetting
    {
        return UserSetting::find($this->id);
    }

    public function loadModel(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $settings = $this->settings();
        $this->max_amount = $settings->max_amount ?? 1000;
        $this->total_bid = $this->getTotalBidAmount();
    }

    public function getTotalBidAmount(): int
    {
        return ItemBid::query()
            ->where([
                'user_id' => $this->id,
                'is_last' => 1
            ])
            ->sum('amount');
    }

    public function hasBalanceForBid(int $amount): bool
    {
        return $this->max_amount - $this->total_bid >= $amount;
    }

}
