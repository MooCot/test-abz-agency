<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\Api\CreateUserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\PersonalAccessToken;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

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
                "users" => UserResource::collection($users->items())
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    public function create(CreateUserRequest $request)
    {
        try {
            $token = $request->header('Token');
            if (PersonalAccessToken::expiresToken($token)) {
                throw new \Exception("The token expired.", JsonResponse::HTTP_UNAUTHORIZED);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $fullFilePath = Storage::path('public/' . $path);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'positions_id' => $request->positions_id,
                'photo' => $path,
            ]);

            User::copressImg($fullFilePath);

            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                "message" => "New user successfully registered"]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], (int)$e->getCode());
        }
    }

    public function show(Request $request)
    {
        try {
            $validator = Validator::make(["id" => $request->id], [
                'id' => 'required|int',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            return response()->json([
                'success' => true,
                "user" => new UserResource(User::findOrFail($request->id))]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "success" => false,
                "message" => "The user with the requested identifier does not exist",
                $e->getMessage()
            ], JsonResponse::HTTP_NOT_FOUND);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                "message" => "Validation failed",
                'fails' => $e->validator->errors(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                "message" => $e->getMessage(),
            ], $e->getCode());
        }
    }

    public function token(Request $request)
    {
        try {
            $toketnString = hash('sha256', Str::random(60));
            PersonalAccessToken::create([
                'name' => 'custom-token-name',
                'token' => $toketnString,
            ]);
            return response()->json([
            'success' => true,
            'token' => $toketnString ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
