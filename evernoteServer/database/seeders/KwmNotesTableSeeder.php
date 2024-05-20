<?php

namespace Database\Seeders;

use App\Models\Kwmlist;
use App\Models\Kwmnote;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KwmNotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first list
        $kwmnlist = Kwmlist::first();
        // Create a new note
        $kwmnote = new Kwmnote();

        // Set the note properties and save it to the database using the save method
        $kwmnote->noteTitle = 'Testnote 1';
        $kwmnote->noteDescription = 'This is a test description for Testnote 1';
        $kwmnote->kwmlists_id = $kwmnlist->id;

        $kwmnote->save();

    }
}
