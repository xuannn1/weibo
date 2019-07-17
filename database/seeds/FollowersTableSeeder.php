<?php

use Illuminate\Database\Seeder;
use App\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;

        //获取除去id为1的其它所有用户的id数组
        $followers = $users->slice(1);
        $follower_ids = $followers->pluck('id')->toArray();

        //关注除了1号用户以外的所有用户
        $user->follow($follower_ids);

        // 除了1号用户以外的所有用户 都来关注1号用户
        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}
