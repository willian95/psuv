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
				"name" => "ACOSTA",
				"last_name" => "ACOSTA",
				"email" => strtolower("ACOSTA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "ACOSTA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "BOLIVAR",
				"last_name" => "BOLIVAR",
				"email" => strtolower("BOLIVAR@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "BOLIVAR")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "BUCHIVACOA",
				"last_name" => "BUCHIVACOA",
				"email" => strtolower("BUCHIVACOA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "BUCHIVACOA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "CARIRUBANA",
				"last_name" => "CARIRUBANA",
				"email" => strtolower("CARIRUBANA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "CARIRUBANA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "COLINA",
				"last_name" => "COLINA",
				"email" => strtolower("COLINA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "COLINA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "DEMOCRACIA",
				"last_name" => "DEMOCRACIA",
				"email" => strtolower("DEMOCRACIA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "DEMOCRACIA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "FALCON",
				"last_name" => "FALCON",
				"email" => strtolower("FALCON@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "FALCON")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "FEDERACION",
				"last_name" => "FEDERACION",
				"email" => strtolower("FEDERACION@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "FEDERACION")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "MAUROA",
				"last_name" => "MAUROA",
				"email" => strtolower("MAUROA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "MAUROA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "MIRANDA",
				"last_name" => "MIRANDA",
				"email" => strtolower("MIRANDA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "MIRANDA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "PETIT",
				"last_name" => "PETIT",
				"email" => strtolower("PETIT@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "PETIT")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "SILVA",
				"last_name" => "SILVA",
				"email" => strtolower("SILVA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "SILVA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "ZAMORA",
				"last_name" => "ZAMORA",
				"email" => strtolower("ZAMORA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "ZAMORA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "DABAJURO",
				"last_name" => "DABAJURO",
				"email" => strtolower("DABAJURO@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "DABAJURO")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "MONS. ITURRIZA",
				"last_name" => "MONS. ITURRIZA",
				"email" => strtolower("ITURRIZA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "MONS. ITURRIZA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "LOS TAQUES",
				"last_name" => "LOS TAQUES",
				"email" => strtolower("LOSTAQUES@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "LOS TAQUES")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "PIRITU",
				"last_name" => "PIRITU",
				"email" => strtolower("PIRITU@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "PIRITU")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "UNION",
				"last_name" => "UNION",
				"email" => strtolower("UNION@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "UNION")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "SAN FRANCISCO",
				"last_name" => "SAN FRANCISCO",
				"email" => strtolower("SANFRANCISCO@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "SAN FRANCISCO")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "JACURA",
				"last_name" => "JACURA",
				"email" => strtolower("JACURA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "JACURA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "CACIQUE MANAURE",
				"last_name" => "CACIQUE MANAURE",
				"email" => strtolower("MANAURE@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "CACIQUE MANAURE")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "PALMASOLA",
				"last_name" => "PALMASOLA",
				"email" => strtolower("PALMASOLA@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "PALMASOLA")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "SUCRE",
				"last_name" => "SUCRE",
				"email" => strtolower("SUCRE@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "SUCRE")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "URUMACO",
				"last_name" => "URUMACO",
				"email" => strtolower("URUMACO@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "URUMACO")->first()->id,
				"role" => "admin"
			],
			[
				"name" => "TOCOPERO",
				"last_name" => "TOCOPERO",
				"email" => strtolower("TOCOPERO@psuv.com"),
				"password" => bcrypt("admin123"),
				"municipio_id" => Municipio::where("nombre", "TOCOPERO")->first()->id,
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
