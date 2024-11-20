<?php

namespace Database\Seeders;

use App\Models\Base\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(45)->create();
    }
}