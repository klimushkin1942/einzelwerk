<?php

namespace Database\Seeders;

use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Database\Seeder;

class CounterpartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        Counterparty::factory()->count(5)->create([
            'user_id' => $user->id,
            'inn' => function () {
                return mt_rand(1000000000, 9999999999);
            },
            'ogrn' => function () {
                return mt_rand(1000000000000, 9999999999999);
            },
        ]);
    }
}
