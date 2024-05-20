<?php

namespace App\Http\Controllers;

use App\Models\Kwmtodo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KwmtodoController extends Controller
{
    // searching for all todos in the database
    public function index(): JsonResponse
    {
        $kwmtodos = Kwmtodo::all();
        return response()->json($kwmtodos, 200);
    }

    // searching for a specific todo
    public function findTodo(int $id)
    {
        // searching for the to-do with the specific Id and returning it with the list and the users
        $kwmtodo = Kwmtodo::where('id', $id)->with(['kwmlist.kwmusers'])->first();
        return $kwmtodo != null ? response()->json($kwmtodo, 200) : response()->json(null, 200);
    }

    // checks if ID is already existing or not
    public function checkId(int $id):JsonResponse
    {
        $kwmtodo = Kwmtodo::where('id', $id)->first();
        return $kwmtodo !=null ? response()->json(true, 200) : response()->json(false,200);
    }

    // searching for a specific todo by the search term
    public function findBySearchTerm(string $searchTerm): JsonResponse
    {
        // searching for the to-do with the specific search term and returning it with the list and the users
        $kwmtodos = KwmTodo::with(['kwmlist', 'kwmnote'])
            ->where('todoName', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('todoDescription', 'LIKE', '%' . $searchTerm . '%')
            ->get();

        return response()->json($kwmtodos, 200);
    }

    // saving a new KWM-To-do in the database with the request data and returning the new to-do
    public function save(Request $request): JsonResponse
    {
        // parsing the request to handle the date format correctly
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            // create a new KWM-To-do with the request data and save it in the database
            $kwmtodo = KwmTodo::create([
                'kwmlists_id' => $request['kwmlists_id'],
                'kwmnotes_id' => $request['kwmnotes_id'],
                'todoName' => $request['todoName'],
                'todoDescription' => $request['todoDescription'],
                'due_date' => $request['due_date'] ?? null
            ]);
            DB::commit();
            // return the new to-do with the status code 201 as JSON response
            return response()->json($kwmtodo, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => "Saving new KwmTodo failed: " . $e->getMessage()], 500);
        }
    }

    // deleting KWM-Todos
    public function delete($id): JsonResponse
    {
        try {
            // find the to-do by ID
            $kwmtodo = KwmTodo::findOrFail($id);
            // delete the to-do
            $kwmtodo->delete();

            // return a success message
            return response()->json(['message' => 'KwmTodo successfully deleted'], 200);
            // catch the exception if the to-do is not found
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'KwmTodo not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete KwmTodo: ' . $e->getMessage()], 500);
        }
    }

    // updating KWM-Todos with the request data and the specific ID
    public function update(Request $request, $id): JsonResponse
    {
        // parsing the request to handle the date format correctly
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            // find the to-do by ID and update it with the request data
            $kwmtodo = KwmTodo::findOrFail($id);
            // update the to-do with the request data
            $kwmtodo->update($request->all());

            DB::commit();
            return response()->json($kwmtodo, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'KwmTodo not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update KwmTodo: ' . $e->getMessage()], 500);
        }
    }



    // parsing the request to handle the date format
    private function parseRequest(Request $request): Request
    {
        if ($request->has('created_at')) {
            $date = new \DateTime($request->created_at);
            $request['created_at'] = $date->format('Y-m-d H:i:s');
        }
        if ($request->has('due_date')) {
            $date = new \DateTime($request->due_date);
            $request['due_date'] = $date->format('Y-m-d');
        }
        return $request;
    }





}
