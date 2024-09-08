<?php

namespace App\Http\Services;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserException extends Exception {}

class AuthService
{
    public function getUsers($request)
    {
        try {
            $name = $request->name;
            $email = $request->email;
            $roleName = $request->role_name;
            $sortField = $request->input('sortField', 'id');
            $sortDirection = $request->input('sortDirection', 'asc');
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            $users = User::whereHas('role', function ($query) {
                $query->where('role_name', 'DEVELOPER');
            })->with('role');

            if ($name) {
                $users->whereRaw('lower(name) LIKE ?', ['%' . strtolower($name) . '%']);
            }

            if ($email) {
                $users->whereRaw('lower(email) LIKE ?', ['%' . strtolower($email) . '%']);
            }

            if ($roleName) {
                $users->whereRaw('lower(role_name) LIKE ?', ['%' . strtolower($roleName) . '%']);
            }

            $users = $users
                ->orderBy($sortField, $sortDirection)
                ->paginate($perPage, ['*'], 'page', $page);

            return $users;
        } catch (\Exception $e) {
            throw new UserException('Error fetching users', 500);
        }
    }

    public function getUserById($id)
    {
        try {
            return User::with('role')->findOrFail($id);
        } catch (\Exception) {
            throw new UserException('Error fetching user', 500);
        }
    }

    public function createUser(array $data)
    {
        try {
            User::create($data);
        } catch (\Exception) {
            throw new UserException('Error creating user', 500);
        }
    }

    public function updateUser($id, array $data)
    {
        try {
            $task = User::findOrFail($id);
            $task->update($data);
        } catch (\Exception) {
            throw new UserException('Error updating user', 500);
        }
    }

    public function signInUser($request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                throw new UserException('Invalid credentials', 500);
            }
            $user = Auth::user();
            $role = $user->role;

            return [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' => $role->id,
                    'role_name' => $role->role_name
                ]
            ];
        } catch (\Exception $e) {
            throw new UserException($e->getMessage(), 500);
        }
    }

    public function signOutUser()
    {
        try {
            Auth::logout();
        } catch (\Exception) {
            throw new UserException('Error deactivating user', 500);
        }
    }

    public function getRoleWithPermissions($role_id)
    {
        $role = Role::with('permissions')->findOrFail($role_id);

        return $role->permissions->pluck('permission_name')->toArray();
    }
}
