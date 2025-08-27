<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Raffle;
use App\Models\Djsession;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Raffle>
 */
class RaffleFactory extends Factory
{
    protected $model = Raffle::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dj_id' => User::factory(['role' => 'dj']), 
            'djsession_id' => Djsession::factory(),
            'winner_id' => null,
            'winner_type' => null,
            'prize_name' => $this->faker->word(),
            'prize_quantity' => $this->faker->numberBetween(1, 100),
            'prize_image' => $this->faker->imageUrl(),
            'description' => $this->faker->sentence(),
            'is_current' => false,
            'status' => Raffle::STATUS_DRAFT,
            'participants_count' => 0,
        ];
    }
}
