<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OAuthUser>
 */
class OAuthUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'role' => fake()->randomElement(['ROLE_CUSTOMER', 'ROLE_MANAGER']),
            'profile_image' => "https://www.gravatar.com/avatar/" . md5(fake()->unique()->safeEmail()) . "?d=identicon",
        ];
    }
}
