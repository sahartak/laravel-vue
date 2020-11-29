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
 * @property string $max_bid_amount
 * @property bool $auto_bid
 *
 * @property ItemBid[] $bids
 */
class User
{
    use  Notifiable;

    const DEFAULT_USERS = [
        1 => [
            'id' => 1,
            'name' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => '$2y$10$7ci5zmNcRb4iVHYWd.occOzIbwiXQT7W0iC2WnAme1B/pIC6fXEZG',
            'balance' => '50000',
            'max_bid_amount' => '10000',
            'auto_bid' => 1
        ],
        2 => [
            'id' => 2,
            'name' => 'user2',
            'email' => 'suser2@gmail.com',
            'password' => '$2y$10$RF5jXmgPucCG2D9oKrhqnOB7YGQFmK8MOL8an9iTAhyX9Z6g9UFzu',
            'balance' => '10000',
            'max_bid_amount' => '5000',
            'auto_bid' => 1
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

    public function loadModel(array $data): void
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->max_bid_amount = $data['max_bid_amount'];
        $this->balance = $data['balance'];
        $this->auto_bid = $data['auto_bid'];
    }

    public function hasBalanceForBid(int $amount): bool
    {
        return true; //@todo add logic
    }

}
