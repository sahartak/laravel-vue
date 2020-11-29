<?php

use App\Models\{User, UserSetting};
use Illuminate\Database\Seeder;

class UserSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids = array_keys(User::DEFAULT_USERS);
        foreach ($user_ids as $user_id) {
            $userSetting = new UserSetting();
            $userSetting->user_id = $user_id;
            $userSetting->max_amount = 1000;
            $userSetting->save();
        }
    }
}
