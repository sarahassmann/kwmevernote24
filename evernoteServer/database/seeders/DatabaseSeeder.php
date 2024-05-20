<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // calls KwmListsTableSeeder and other necessary seeders
        $this->call(UsersTableSeeder::class);
        $this->call(KwmListsTableSeeder::class);
        $this->call(KwmNotesTableSeeder::class);
        $this->call(KwmTagsTableSeeder::class);
        $this->call(KwmToDosTableSeeder::class);
    }
}
