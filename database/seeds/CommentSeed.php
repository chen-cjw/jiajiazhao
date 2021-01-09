<?php

use Illuminate\Database\Seeder;

class CommentSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Model\Comment::create([
            'content'=>11111,
            'comment_user_id'=>1,
            'reply_user_id'=>1,
            'information_id'=>1,
        ]);
        \App\Model\Comment::create([
            'content'=>22222,
            'comment_user_id'=>1,
            'reply_user_id'=>1,
            'information_id'=>1,
        ]);
        \App\Model\Comment::create([
            'content'=>33333,
            'comment_user_id'=>1,
            'reply_user_id'=>1,
            'information_id'=>1,
        ]);
        \App\Model\Comment::create([
            'content'=>44444,
            'comment_user_id'=>1,
            'reply_user_id'=>1,
            'information_id'=>1,
        ]);
        factory(\App\Model\Comment::class, 20)->create();

    }
}
