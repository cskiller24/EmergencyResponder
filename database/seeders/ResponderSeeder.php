<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\EmergencyType;
use App\Models\Location;
use App\Models\RelatedLink;
use App\Models\Responder;
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
            ->has(RelatedLink::factory()->count(1, 3))
            ->has(EmergencyType::factory())
            ->has(Contact::factory()->count(mt_rand(1, 3)))
            ->create();
    }
}
