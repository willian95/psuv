<?php

use Illuminate\Database\Seeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seedersArray = [
            RoleSeeder::class,
            UsersSeeder::class,
        ];
        //call seeders
        foreach ($seedersArray as $seeder) $this->call($seeder);
    }
}
