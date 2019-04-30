<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ConfsTableSeeder::class);
        $this->call(OrgsTableSeeder::class);
        $this->call(ShopsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
