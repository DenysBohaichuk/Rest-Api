<?php

namespace Database\Seeders;

use App\Models\Base\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::create(['name' => 'Lawyer']);
        Position::create(['name' => 'Content manager']);
        Position::create(['name' => 'Security']);
        Position::create(['name' => 'Designer']);
    }
}
