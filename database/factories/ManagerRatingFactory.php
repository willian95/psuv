<?php

namespace Database\Factories;

use App\Models\ManagerRating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManagerRatingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ManagerRating::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userClient = User::role('client')->inRandomOrder()->first();

        return [
            'user_id' => $userClient->id,
            'message' => $this->faker->realText(),
            'rating' => rand(1,5)
        ];
    }
}
