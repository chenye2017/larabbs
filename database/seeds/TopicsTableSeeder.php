<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = app(Faker\Generator::class);
        $user_ids = \App\Models\User::all()->pluck('id')->toArray();
        $categories = \App\Models\Category::all()->pluck('id')->toArray();
        $topics = factory(Topic::class)->times(50)->make()->each(function ($topic, $index) use ($faker, $user_ids, $categories) {
            $topic->user_id = $faker->randomElement($user_ids);
            $topic->category_id = $faker->randomElement($categories);
        });

        Topic::insert($topics->toArray());
    }

}

