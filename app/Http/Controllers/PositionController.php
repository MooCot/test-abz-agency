<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Http\Requests\Api\CreateUserRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $users = Position::get();
            if ($users->isEmpty()) {
                throw new \Exception("Positions not found");
            }
            return response()->json(['users' => $users]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 422);
        }
    }
}
