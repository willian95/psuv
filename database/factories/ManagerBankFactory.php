<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\ManagerBank;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManagerBankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ManagerBank::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $bank_ids = Bank::inRandomOrder()->first();
        return [
            'account_number' => $this->faker->bankAccountNumber,
            'bank_id' => $bank_ids->id,
            'type_account' => $this->faker->randomElement(['ahorro', 'corriente'])
        ];
    }
}
