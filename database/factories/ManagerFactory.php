<?php

namespace Database\Factories;

use App\Models\{CourierType, Manager, ManagerType};
use Illuminate\Database\Eloquent\Factories\Factory;

class ManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $return = [
            'approved' => false,
            'available' => rand(0,1),
            'dni_url' => 'https://via.placeholder.com/150'
        ];

        return $return;
    }
}
