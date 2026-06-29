<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserManagementController extends Controller
{
  public function index(Request $request)
  {
    $query = User::query();

    if ($request->filled('search')) {
      $query->where(function ($q) use ($request) {
        $q->where('name', 'like', '%' . $request->search . '%')
          ->orWhere('email', 'like', '%' . $request->search . '%');
      });
    }

    if ($request->filled('role')) {
      $query->where('role', $request->role);
    }

    $users = $query->latest()->paginate(10);

    $totalAdmin = User::where('role', 'admin')->count();
    $totalUser = User::where('role', 'user')->count();
    $total = User::count();

    return view('admin.users.index', compact('users', 'totalAdmin', 'totalUser', 'total'));
  }

  public function updateRole(Request $request, $id)
  {
    $request->validate([
      'role' => 'required|in:admin,user',
    ]);

    $user = User::findOrFail($id);

    if ($user->id === auth()->id()) {
      return response()->json([
        'success' => false,
        'message' => 'Anda tidak dapat mengubah role akun Anda sendiri.'
      ], 403);
    }

    $user->update(['role' => $request->role]);

    return response()->json([
      'success' => true,
      'message' => "Role {$user->name} berhasil diubah menjadi {$request->role}.",
      'role' => $user->role
    ]);
  }

  public function destroy($id)
  {
    $user = User::findOrFail($id);

    if ($user->id === auth()->id()) {
      return response()->json([
        'success' => false,
        'message' => 'Anda tidak dapat menghapus akun Anda sendiri.'
      ], 403);
    }

    $user->delete();

    return response()->json([
      'success' => true,
      'message' => "Akun {$user->name} berhasil dihapus."
    ]);
  }
}