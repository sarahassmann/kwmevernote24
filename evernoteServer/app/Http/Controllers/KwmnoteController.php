<?php

namespace App\Http\Controllers;

use App\Models\Kwmnote;
use App\Models\Kwmtag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KwmnoteController extends Controller
{
    // searching for all notes
    public function index(): JsonResponse
    {
        $kwmnotes = Kwmnote::all();
        return response()->json($kwmnotes, 200);
    }

    // searching for a specific note
    public function findByNotesId(int $id)
    {
        // get the note by ID with the list and tags relations and users
        $kwmnote = Kwmnote::with(['list.kwmusers', 'tags'])
            ->where('id', $id)
            ->first();
        // return the note as JSON response if it exists or null if it does not
        return response()->json($kwmnote ?? null);
    }


    // checks if ID is already existing or not
    public function checkNoteId(int $id):JsonResponse
    {
        $kwmnote = Kwmnote::where('id', $id)->first();
        return $kwmnote !=null ? response()->json(true, 200) : response()->json(false,200);
    }

    // searching for a note by a search term
    public function findBySearchTerm (string $searchTerm): JsonResponse
    {
        // get all notes with the list and tags relations where the title or description contains the search term
        $kwmnotes = Kwmnote::with(['list', 'tags'])
            ->where('noteTitle', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('noteDescription', 'LIKE', '%' . $searchTerm . '%')
            ->get();
        return response()->json($kwmnotes, 200);
    }

    // saving a note with tags and lists in a transaction to ensure data consistency
    public function save(Request $request): JsonResponse
    {
        // parsing the request to ensure the date is in the correct format
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            // create a new note with the data from the request and save it to the database
            $kwmnote = Kwmnote::create($request->all());
            // check if tags are present in the request and save them to the database if they are present
            if (isset($request['tags']) && is_array($request['tags'])) {
                foreach ($request['tags'] as $tagData) {
                    // create a new tag if it does not exist yet or find the existing tag by name if it exists already
                    $tag = Kwmtag::firstOrNew(['tagName' => $tagData['tagName']]);
                    // save the tag to the database if it is new and attach it to the note
                    $kwmnote->tags()->save($tag);
                }
            }
            DB::commit();
            // return the note as JSON response with the status code 201 (created) if the note was saved successfully
            return response()->json($kwmnote, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            // return an error message as JSON response with the status code 420 (method failure) if an error occurred
            return response()->json("Saving new kwmnote failed: " . $e->getMessage(), 420);
        }
    }

    // parsing the request
    private function parseRequest(Request $request): Request
    {
        if ($request->has('created_at')) {
            $date = new \DateTime($request->created_at);
            $request['created_at'] = $date->format('Y-m-d H:i:s');
        }
        return $request;
    }

    // deleting a note
    public function delete($id): JsonResponse
    {
        try {
            // find the note by ID
            $kwmnote = Kwmnote::findOrFail($id);
            // delete the note
            $kwmnote->delete();

            // return a success message
            return response()->json(['message' => 'Kwmnote successfully deleted'], 200);
            // catch the exception if the note is not found
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Kwmnote not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete Kwmnote: ' . $e->getMessage()], 500);
        }
    }

    // updating a note
    public function update(Request $request, $id): JsonResponse
    {
        // parse the request to ensure the date is in the correct format
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            // finds the note by ID or throws an exception
            $kwmnote = Kwmnote::findOrFail($id);
            // Update the note with the data from the request
            $kwmnote->update($request->all());

            // update the tags if they are present in the request
            if (isset($request['tags']) && is_array($request['tags'])) {
                // create an array to store the tag IDs for the note from the request data
                $tagIds = [];
                // loop through the tags in the request data and find or create the tags in the database and store their IDs
                foreach ($request['tags'] as $tagData) {
                    // find or create the tag by name and store the ID in the array of tag IDs
                    $tag = Kwmtag::firstOrCreate(['tagName' => $tagData['tagName']]);
                    // store the ID of the tag in the array of tag IDs for the note
                    $tagIds[] = $tag->id;
                }
                // sync the tags for the note
                $kwmnote->tags()->sync($tagIds);
            }
            DB::commit();
            return response()->json($kwmnote, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Kwmnote not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update Kwmnote: ' . $e->getMessage()], 500);
        }
    }


}
