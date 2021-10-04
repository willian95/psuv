<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Municipio;

class UsersSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$array = [
			[
				"name" => "Sabas",
				"last_name" => "Admin",
				"email" => "admin@psuv.com",
				"password" => bcrypt("admin123"),
				"role" => "admin"
			],
			[
				"name" => "Carirubana",
				"last_name" => "Carirubana",
				"email" => "carirubana@psuv.com",
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "CARIRUBANA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "Miranda",
				"last_name" => "Miranda",
				"email" => "miranda@psuv.com",
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "MIRANDA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "FALCON",
				"last_name" => "FALCON",
				"email" => "falcon@psuv.com",
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "FALCON")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "LOS TAQUES",
				"last_name" => "LOS TAQUES",
				"email" => "lostaques@psuv.com",
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "LOS TAQUES")->first()->id,
				"role" => "admin"
			],
		];
		foreach ($array as $arr) {
			$u = User::firstOrCreate([
				"email" => $arr['email']
			], collect($arr)->except('role')->all());
			if (!$u->hasRole($arr['role']))
				$u->assignRole($arr['role']);
		}
	}
}
