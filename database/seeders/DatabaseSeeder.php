<?php

namespace Database\Seeders;

use App\Models\Club;
use App\Models\Tenant;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Roger Aspelin',
//            'email' => 'raspelin69@gmail.com',
//        ]);

        $tenant = Tenant::query()->create(
            attributes: [
                'id' => 'foo',
            ]
        );

        $tenant->domains()->create(
            attributes: [
                'domain' => 'foo.judoadmin.local',
            ]
        );

        Tenant::all()->runForEach(function (Tenant $tenant) {
            $user = User::factory()->create([
                'name' => 'Roger Aspelin',
                'email' => 'raspelin69@gmail.com',
            ]);
            Club::factory()->for($user)->create([
                'name' => 'Judo Club 1',
            ]);
        });




    }
}
