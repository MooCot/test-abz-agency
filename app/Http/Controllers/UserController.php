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
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'count' => 'int',
                'page' => 'int|min:1',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $count = $request->input('count', 5);
            $users = User::paginate($count);
            if ((int)$users->lastPage() < (int)$request->page) {
                throw new \Exception("Page not found");
            }
            return response()->json([
                'success' => true,
                'page' => $users->currentPage(),
                'total_pages' => $users->lastPage(),
                'total_users' => $users->total(),
                'count' => $count,
                "links" => [
                    "next_url" => $users->nextPageUrl(),
                    "prev_url" => $users->previousPageUrl()
                ],
                "users" => $users->items()
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 404);
        }
    }

    public function create(CreateUserRequest $request)
    {
        try {
            $path = $request->file('photo')->store('photos', 'public');
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->positions_id = $request->positions_id;
            $user->photo = $path;
            $user->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|nameric',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $user = User::findOrFail($request->id);
            return response()->json(['success' => true, "user" => $user]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "The user with the requested identifier does not exist",
                $e->getMessage()], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors(),
            ], 400);
        }
    }
}
