<?php

namespace Database\Seeders;

use App\Models\Kwmlist;
use App\Models\Kwmtag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KwmTagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create kwmtags
        $kwmTag = new Kwmtag();
        $kwmTag1 = new Kwmtag();
        $kwmTag2 = new Kwmtag();

        // give kwmtags some values and save them to the database
        $kwmTag->tagName = 'Test-Tag 1';
        $kwmTag1->tagName = 'Test-Tag 2';
        $kwmTag2->tagName = 'Test-Tag 3';

        // save the kwmtags
        $kwmTag->save();
        $kwmTag1->save();
        $kwmTag2->save();

    }
}
