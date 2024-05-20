<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Kwmlist;
use App\Models\User;
use Faker\Core\DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KwmListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // get the first user
        $user = User::first();

        // create and save the first list
        $kwmlist = new Kwmlist();
        $kwmlist->listName = 'Testlist 1';
        // save into DB
        $kwmlist->save();
        // attach the user to the list
        $kwmlist->kwmusers()->attach($user->id);

        // add pictures to the list
        $image1 = new Image(['title' => 'Cover 1', 'url' => 'https://cdn-icons-png.flaticon.com/512/654/654116.png']);
        $image2 = new Image(['title' => 'Cover 2', 'url' => 'https://cdn-icons-png.freepik.com/512/8161/8161879.png']);
        $kwmlist->images()->saveMany([$image1, $image2]);

        // create and save the second list
        $kwmlist2 = new Kwmlist();
        $kwmlist2->listName = 'Testlist 2';
        $kwmlist2->save();
        $kwmlist2->kwmusers()->attach($user->id);
    }
}
