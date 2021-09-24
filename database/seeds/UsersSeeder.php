<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

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
			]
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
