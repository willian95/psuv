<?php

namespace Database\Factories;

use App\Models\{Order, Manager, OrderStatus, Procedure};
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $status = OrderStatus::inRandomOrder()->first();
        $procedure = Procedure::inRandomOrder()->first();

        return [
            'applicant_name' => $this->faker->name,
            'applicant_dni' => random_int(111111,999999),
            'applicant_email' => $this->faker->safeEmail,
            'address' => $this->faker->streetAddress,
            'delivery_date' => $this->faker->dateTimeThisMonth(),
            'total' => $this->faker->randomFloat(2),
            'procedure_id' => $procedure->id,
            'order_status_id' => $status->id
        ];
    }
}
