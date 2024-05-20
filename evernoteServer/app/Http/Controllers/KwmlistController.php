<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Kwmlist;
use App\Models\Kwmnote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KwmlistController extends Controller
{
    // Retrieve and display all Kwmlists along with their related notes, images, and kwmusers
    public function index():JsonResponse
    {
        $kwmlists = Kwmlist::with(['notes', 'images', 'kwmusers'])->get();
        return response()->json($kwmlists,200);
    }

    // Find a single Kwmlist by ID including its related data (notes, images, kwmusers)
    public function findById(int $id):JsonResponse
    {
        $kwmlist = Kwmlist::where('id', $id)->with(['notes', 'images', 'kwmusers'])->first();
        return $kwmlist !=null ? response()->json($kwmlist, 200) : response()->json(null,200);
    }

    // Check if a Kwmlist with the specified ID exists and return a boolean result
    public function checkId(int $id):JsonResponse
    {
        $kwmlist = Kwmlist::where('id', $id)->first();
        return $kwmlist !=null ? response()->json(true, 200) : response()->json(false,200);
    }

    // Search Kwmlists by a search term in the 'listName' field
    public function findBySearchTerm(string $searchTerm):JsonResponse
    {
        $kwmlist = Kwmlist::with(['notes', 'images', 'kwmusers'])->where('listName', 'LIKE', '%'.$searchTerm.'%')->get();
        return response()->json($kwmlist, 200);
    }

    // Delete a Kwmlist by its ID and handle possible exceptions
    public function delete(int $id): JsonResponse
    {
        $kwmlist = Kwmlist::find($id);

        if ($kwmlist) {
            try {
                $kwmlist->delete();
                return response()->json(['message' => 'Kwmlist successfully deleted'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Delete operation failed', 'error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['message' => 'Kwmlist not found'], 404);
        }
    }


    // Create Kwmlist and associated images and notes within a database transaction
    public function save(Request $request):JsonResponse
    {
        $request = $this->parseRequest($request);
        // Start a DB-Transaction
        DB::beginTransaction();
        try {
            $kwmlist = Kwmlist::create($request->all());
            if (isset($request['images']) && is_array($request['images'])) {
                foreach ($request['images'] as $img) {
                    $image = Image::firstOrNew(['url'=>$img['url'], 'title'=>$img['title']]);
                    $kwmlist->images()->save($image);
                }
            }
            // if there are notes, create them and attach them to the kwmlist object
            if (isset($request['notes']) && is_array($request['notes'])) {
                foreach ($request['notes'] as $noteData) {
                    $note = Kwmnote::firstOrNew(['noteTitle' => $noteData['noteTitle'], 'noteDescription' => $noteData['noteDescription']]);
                    $kwmlist->notes()->save($note);
                }
            }
            DB::commit();
            return response()->json($kwmlist, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("Saving new kwmlist failed" .$e->getMessage(), 420);
        }
    }

    // Update an existing Kwmlist based on given ID and request data within a transaction
    public function update(Request $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            // find the Kwmlist by ID and update it
            $kwmlist = Kwmlist::where('id', $id)->first();
            if ($kwmlist != null) {
                // Parse the request
                $request = $this->parseRequest($request);
                // Update the Kwmlist
                $kwmlist->update($request->all());
                // Update the kwmlist
                $kwmlist->save();
            } else {
                // if no Kwmlist is found, rollback the transaction
                DB::rollBack();
                return response()->json("No kwmlist found with id: $id", 404);
            }
            // Commit the transaction if everything went well
            DB::commit();
            // load the updated Kwmlist and return it
            $updatedKwmlist = Kwmlist::where('id', $id)->first();
            return response()->json($updatedKwmlist, 200);
        } catch (\Exception $e) {
            // if an error occurred, rollback the transaction and return an error message
            DB::rollBack();
            return response()->json("Updating kwmlist failed: " . $e->getMessage(), 420);
        }
    }

    // Helper function to parse and adjust the request data, especially handling date formats
    private function parseRequest($request): Request
    {
        // get date and convert it - it is in ISO 8601, ex. "2024-03-22T13:06:00:000Z"
        $date = new \DateTime($request->created_at);
        $request['created_at'] = $date->format('Y-m-d H:i:s');
        $request['updated_at'] = $date->format('Y-m-d H:i:s');
        return $request;
    }
}
