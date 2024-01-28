<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Position;
use App\Http\Requests\AdminUserRequest;
use App\Http\Resources\UserResource;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $countUser = 6;
        return view('welcome', [
            'users' => UserResource::collection(User::paginate($countUser)->items()),
        ]);
    }

    public function loadMoreUsers(Request $request)
    {
        $offset = $request->input('offset', 0);

        $users = User::skip($offset)->take(6)->get();

        return response()->json(['users' => UserResource::collection($users)]);
    }

    public function create()
    {
        $positions = Position::get();
        return view('admin.create', [
            'positions' => $positions,
        ]);
    }

    public function store(AdminUserRequest $request)
    {
        $path = $request->file('photo')->store('photos', 'public');
        $fullFilePath = Storage::path('public/' . $path);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'positions_id' => $request->position,
            'photo' => $path,
        ]);

        User::copressImg($fullFilePath);

        return back()->withStatus(__('User successfully create.'));
    }
}
