<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Djsession;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Djsession>
 */
class DjsessionFactory extends Factory
{
    protected $model = Djsession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('SESSION-####'),
            'name' => $this->faker->catchPhrase(),
            'image' => null,
            'active' => $this->faker->boolean(30),
            'venue' => $this->faker->company(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'description' => $this->faker->paragraph(3),
            'start_time' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
            'end_time' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'song_request_timeout' => $this->faker->numberBetween(30, 300),
        ];
    }
}