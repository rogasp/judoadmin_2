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

        User::factory()->create([
            'name' => 'Roger Aspelin',
            'email' => 'raspelin69@gmail.com',
        ]);

        $data = [
            'domain' => 'foo',
            'name' => 'Roger Aspelin',
            'email' => 'raspelin69gmail.com',
            'password' => 'password',
        ];

        $data['password'] = bcrypt($data['password']);

        $domain = $data['domain'];
        unset($data['domain']);

        $tenant = Tenant::create($data + [
                'ready' => false,
                // some other stuff, if you need. like cashier trials
            ]);


        $tenant->domains()->create(
            attributes: [
                'domain' => 'foo.judoadmin.local',
            ]
        );

    }
}
