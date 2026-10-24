<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserService
{
    public function list(): Collection
    {
        return User::select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('id')
            ->get();
    }

    public function create(User $actor, array $data): User
    {
        if ($actor->role === 'admin' && $data['role'] !== 'user') {
            abort(403, 'Un admin ne peut créer que des utilisateurs simples.');
        }

        return User::create($data);
    }
    
    public function update(User $actor, User $target, array $data): User
    {
        if ($actor->role === 'admin' && $target->role !== 'user') {
            abort(403, 'Un admin ne peut modifier que des utilisateurs simples.');
        }

        if ($actor->role === 'admin' && $data['role'] !== 'user') {
            abort(403, 'Un admin ne peut attribuer que le rôle user.');
        }

        if ($actor->id === $target->id && $data['role'] !== $target->role) {
            abort(403, 'Vous ne pouvez pas changer votre propre rôle.');
        }

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $target->update($data);

        return $target;
    }

    public function delete(User $actor, User $target): void
    {
        if ($actor->id === $target->id) {
            abort(403, 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        if ($actor->role === 'admin' && $target->role !== 'user') {
            abort(403, 'Un admin ne peut supprimer que des utilisateurs simples.');
        }

        $target->tokens()->delete();
        $target->delete();;
    }
}