<?php

namespace Database\Seeders;

use App\Models\EmergencyType;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Responder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResponderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Responder::factory()
            ->count(10)
            ->has(Location::factory())
            ->has(RelatedLink::factory())
            ->has(EmergencyType::factory())
            ->create();
    }
}
