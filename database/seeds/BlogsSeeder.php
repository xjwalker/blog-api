<?php

use Illuminate\Database\Seeder;

class BlogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::today()->subDays(3);
        $users = \App\Models\User::query()->get();
        foreach ($users as $user) {
            $now->addDay();
            \Illuminate\Support\Facades\DB::table('blogs')->insert([
                [
                    'user_id' => $user->id,
                    'title' => $now->toDateTimeString() . 'Lorem Ipsum is simply dummy text of the printing and' .
                        ' typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever',
                    'content' => $now->toDateTimeString() . ' Essentially unchanged. It was popularised in the 1960s' .
                        ' with the release of Letraset sheets containing Lorem Ipsum passages, and more',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'user_id' => $user->id,
                    'title' => $now->toDateTimeString() . 'Lorem Ipsum is simply dummy text of the printing and' .
                        ' typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever',
                    'content' => $now->toDateTimeString() . ' Essentially unchanged. It was popularised in the 1960s' .
                        ' with the release of Letraset sheets containing Lorem Ipsum passages, and more',
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ]);
        }
    }
}
