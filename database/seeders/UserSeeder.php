<?php

namespace Database\Seeders;

use App\Models\Base\Position;
use App\Models\Base\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::factory(45)->create();

        $users->each(function ($user) {
            $randomPosition = Position::inRandomOrder()->first();
            $user->positions()->attach($randomPosition->id);
        });
    }
}
