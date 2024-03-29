<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Http\JsonResponse;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $users = Position::get();
            if ($users->isEmpty()) {
                throw new \Exception("Positions not found");
            }
            return response()->json([
                'success' => true,
                'users' => $users]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
