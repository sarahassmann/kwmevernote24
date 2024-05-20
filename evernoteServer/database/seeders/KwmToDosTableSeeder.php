<?php

namespace Database\Seeders;

use App\Models\Kwmlist;
use App\Models\Kwmnote;
use App\Models\Kwmtag;
use App\Models\Kwmtodo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class KwmToDosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve the first instance of Kwmlist and Kwmnote from the database
        $kwmlist = Kwmlist::first();
        $kwmnote = Kwmnote::first();

        // Create a new Kwmtodo object
        $kwmtodo = new Kwmtodo();
        // Set the attributes of the Kwmtodo object
        $kwmtodo->todoName = 'First To-Do Task';
        $kwmtodo->todoDescription = 'This is a test description.';
        $kwmtodo->due_date = now(); // Set the due date to the current date

        // Associate the Kwmtodo object with the Kwmlist and Kwmnote
        $kwmtodo->kwmlists_id = $kwmlist->id;
        $kwmtodo->kwmnotes_id = $kwmnote->id;

        // Save the Kwmtodo object to the database
        $kwmtodo->save();

        // get tag ids from the database
        $tagIds = [1, 2, 3]; // Beispiel-Tag-IDs

        // Attach the tags to the Kwmtodo object and sync the relationship
        $kwmtodo->tags()->sync($tagIds);


    }
}
