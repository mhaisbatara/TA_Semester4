<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $users = User::where('role', 'user')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalUsers  = User::where('role', 'user')->count();
        $totalAdmin  = User::where('role', 'admin')->count();
        $totalMember = User::where('role', 'user')->count();

        return view('auth.admin.data_user', compact('users', 'totalUsers', 'totalAdmin', 'totalMember', 'search'));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
