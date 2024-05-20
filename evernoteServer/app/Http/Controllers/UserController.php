<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // returns all users from the database as JSON
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json($users, 200);
    }

    // searching for a specific user by ID
    public function findById(int $id)
    {
        // load the user with the kwmlists relation (if available) and return it as JSON
        $user = User::with(['kwmlists'])
        ->where('id', $id)
            ->first();
        // check if user is available and return it or return null if not
        return $user != null ? response()->json($user, 200) : response()->json(null, 200);
    }

    // checks if User ID is already existing or not
    public function checkUserId(int $id): JsonResponse
    {
        $user = User::where('id', $id)->first();
        return $user != null ? response()->json(true, 200) : response()->json(false, 200);
    }

    // searching for a user by a search term
    public function findBySearchTerm(string $searchTerm): JsonResponse
    {
        // search for the search term in the name and email columns of the users table
        $users = User::where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
            ->get();
        return response()->json($users, 200);
    }

    // saving a new user to the database and returning the user as JSON
    public function save(Request $request): JsonResponse
    {
        // start a transaction to save the user to the database
        DB::beginTransaction();
        try {
            // validate the request data and return errors if validation fails
            $validatedData = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8'
            ]);

            // if validation fails, return the errors as JSON response with a 422 status code
            if ($validatedData->fails()) {
                return response()->json(['errors' => $validatedData->errors()], 422);
            }
            // create a new user with the validated data and save it to the database
            $user = User::create([
                'name' => $validatedData->validated()['name'],
                'firstName' => $validatedData->validated()['firstName'],
                'lastName' => $validatedData->validated()['lastName'],
                'email' => $validatedData->validated()['email'],
                // hash the password before saving it to the database
                'password' => Hash::make($validatedData->validated()['password']),
            ]);
            DB::commit();
            // return the user as JSON response with a 201 status code
            return response()->json($user, 201);
        } catch (\Exception $e) {
            // rollback the transaction if an error occurs and return an error message
            DB::rollBack();
            return response()->json(['message' => "Saving new user failed: " . $e->getMessage()], 500);
        }
    }

    // deleting a user
    public function delete($id): JsonResponse
    {
        try {
            // find the user by ID
            $user = User::findOrFail($id);
            // delete the user
            $user->delete();

            // return a success message
            return response()->json(['message' => 'User successfully deleted'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        // Parse the request to handle the date format
        $request = $this->parseRequest($request);
        DB::beginTransaction();
        try {
            // find the user by ID and return a 404 status code if the user is not found
            $user = User::findOrFail($id);
            // Validate the request data and return errors if validation fails
            $validatedData = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'firstName' => 'sometimes|required|string|max:255',
                'lastName' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
                'password' => 'sometimes|required|string|min:8'
            ]);

            if ($validatedData->fails()) {
                DB::rollBack();
                return response()->json(['errors' => $validatedData->errors()], 422);
            }
            // update the user with the validated data and save it to the database
            $user->update($validatedData->validated());
            // hash the password before saving it to the database
            if ($request->has('password')) {
                $user->password = Hash::make($request->get('password'));
                $user->save();
            }

            DB::commit();
            return response()->json($user, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update user: ' . $e->getMessage()], 500);
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
