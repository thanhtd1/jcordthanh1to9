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
        $this->call(adminsSeeder::class);
        $this->call(clientSeeder::class);
        $this->call(productTypeSeeder::class);
        $this->call(productSeeder::class);
    }
}
