<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SocialPost;
use App\Models\SocialAccount;
use App\Models\Djsession;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialPost>
 */
class SocialPostFactory extends Factory
{
    protected $model = SocialPost::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'djsession_id' => Djsession::factory(),
            'social_account_id' => SocialAccount::factory(),
            'platform' => 'instagram',
            'media_id' => $this->faker->numerify('#################'),
            'is_active' => true,
            'caption' => $this->faker->sentence(10),
            'media_url' => $this->faker->imageUrl(),
            'permalink' => $this->faker->url,
        ];
    }
}
