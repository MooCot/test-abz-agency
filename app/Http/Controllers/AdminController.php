<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Position;
use App\Http\Requests\AdminUserRequest;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        return view('welcome', [
            'users' => User::paginate(6),
        ]);
    }

    public function loadMoreUsers(Request $request)
    {
        $offset = $request->input('offset', 0);

        $users = User::skip($offset)->take(6)->get();

        return response()->json(['users' => $users]);
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
            'positions_id' => $request->positions_id,
            'photo' => $fullFilePath,
        ]);
        return back()->withStatus(__('Server successfully create.'));
    }
}
