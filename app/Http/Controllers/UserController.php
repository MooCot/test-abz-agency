<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Api\CreateUserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request) {
        $perPage = 5;
        $users = User::paginate($perPage);
        return response()->json(['success' => true, 'users' => $users]);
    }

    public function create(CreateUserRequest $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->name = $request->name;
            $user->positions_id = $request->positions_id;
            $user->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|nameric',
        ]);

        try {
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = User::findOrFail($request->id);
            return response()->json(['success' => true, "user" => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "The user with the requested identifier does not exist",
                $e->getMessage()]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors(),
            ], 400);
        }
    }
}
