<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userIds = Position::pluck('id')->toArray();

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => "+380" . $this->faker->unique()->numberBetween($min = 1000, $max = 9999),
            'photo' => Str::random(10),
            'password' => Hash::make(Str::random(10)),
            'remember_token' => Str::random(10),
            'positions_id' => $this->faker->randomElement($userIds),
        ];
    }
}
