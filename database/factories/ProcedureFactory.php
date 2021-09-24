<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\ManagerType;
use App\Models\Procedure;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProcedureFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Procedure::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $institution = Institution::inRandomOrder()->first();
        // $managerType = ManagerType::inRandomOrder()->first();

        return [
            'name' => ucfirst($this->faker->sentence),
            'estimated_time' => rand(1,30),
            'cost' => $this->faker->randomFloat(2),
            'institution_id' => $institution->id,
            // 'manager_type_id' => $managerType->id
        ];
    }
}
