<?php

namespace Database\Factories;

use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Counterparty>
 */
class CounterpartyFactory extends Factory
{
    protected $model = Counterparty::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'inn' => $this->faker->unique()->numerify('##########'), // 10-значный ИНН
            'ogrn' => $this->faker->unique()->numerify('#############'), // 13-значный ОГРН
            'address' => $this->faker->address,
            'user_id' => User::factory(), // Связь с пользователем
        ];
    }
}
