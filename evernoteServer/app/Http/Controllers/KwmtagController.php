<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Kwmtag;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class KwmtagController extends Controller
{
    // searching for all tags
    public function index(): JsonResponse
    {
        $tags = Kwmtag::all();
        return response()->json($tags);
    }

    // searching for a tag by id and returning it as a json response
    public function findById(int $id): JsonResponse
    {
        $tags = Kwmtag::where('id', $id)->first();
        return response()->json($tags);
    }

    // checking if a tag exists by id and returning a json response (true if it exists, false if not)
    public function checkTagId(int $id): JsonResponse
    {
        $tags = Kwmtag::where('id', $id)->first();
        return $tags != null ? response()->json(true, 200) : response()->json(false, 200);
    }

    // searching for a tag by name and returning it as a json response
    public function findBySearchTerm(string $searchTerm) : JsonResponse
    {
        $tags = Kwmtag::where('tagName', 'LIKE', '%' . $searchTerm . '%')->get();
        return response()->json($tags);
    }

    // saving a tag in the database and returning it as a json response with a 201 status code
    public function save(Request $request): JsonResponse
    {
        // parsing the request and saving the tag in the database
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            // create new tag with the request data (tagName)
            $tag = Kwmtag::create($request->all());
            DB::commit();
            // return a valid http response
            return response()->json($tag, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("updating register failed: " . $e->getMessage(), 420);
        }
    }

    // deleting a tag by id and returning a json response with a 200 status code if the deletion was successful
    public function delete($id): JsonResponse
    {
        try {
            // find the tag by id
            $kwmtag = KwmTag::findOrFail($id);
            // delete the tag from the database
            $kwmtag->delete();

            // message for successful deletion
            return response()->json(['message' => 'KwmTag successfully deleted'], 200);
            // if the tag is not found in the database return a 404 error
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'KwmTag not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete KwmTag: ' . $e->getMessage()], 500);
        }
    }

    // updating a tag by id and returning a json response with the updated tag or an error message
    public function update(Request $request, $id): JsonResponse
    {
        // parsing the request
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            // find or fail the tag by id and update it with the request data
            $kwmtag = KwmTag::findOrFail($id);
            // update the tag with the request data
            $kwmtag->update($request->all());

            DB::commit();
            return response()->json($kwmtag, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'KwmTag not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update KwmTag: ' . $e->getMessage()], 500);
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


}
