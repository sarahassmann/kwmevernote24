<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        $user = new User();
        // Set the user's name, email, and password
        $user->name = 'testuser';
        $user->email = 'test@gmail.com';
        $user->password = bcrypt('secret');

        // create a second user
        $user2 = new User();
        $user2->name = 'testuser2';
        $user2->email = 'test2@gmail.com';
        $user2->password = bcrypt('secret2');
        $user2->firstName = 'testName2';
        $user2->lastName = 'testLastName2';
        // Save user to the database
        $user->save();
        $user2->save();
    }
}
