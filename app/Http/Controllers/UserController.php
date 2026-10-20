<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('id')
            ->get();

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'user'])],
        ]);

        if ($request->user()->role === 'admin' && $data['role'] !== 'user') {
            return response()->json([
                'message' => 'Un admin ne peut créer que des utilisateurs simples.',
            ], 403);
        }

        $user = User::create($data);

        return response()->json($user, 201);
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $current = $request->user();

        if ($current->id === $user->id) {
            return response()->json([
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.',
            ], 403);
        }

        if ($current->role === 'admin' && $user->role !== 'user') {
            return response()->json([
                'message' => 'Un admin ne peut supprimer que des utilisateurs simples.',
            ], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé.']);
    }
}