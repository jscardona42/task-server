<?php

namespace App\Http\Controllers;

use App\Http\Services\AuthService;
use App\Models\Role;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(Request $request)
    {
        try {
            $users = $this->authService->getUsers($request);
            return new JsonResponse($users);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $user = $this->authService->getUserById($id);
            return new JsonResponse($user);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $role = Role::where('role_name', $request->role)->first();
            $request->merge(["role_id" => $role->id]);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email',
                'password' => 'required|string',
                'role_id' => 'required|exists:roles,id'
            ]);

            $validatedData['password'] = Hash::make($request->password);
            $this->authService->createUser($validatedData);
            return new JsonResponse(['message' => 'User successfully created']);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email',
                'role_id' => 'nullable|exists:roles,id'
            ]);

            $this->authService->updateUser($id, $validatedData);
            return new JsonResponse(['message' => 'User successfully updated']);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $data = $this->authService->signInUser($request);
            $permissions = $this->authService->getRoleWithPermissions($data['user']['role_id']);
            $data['permissions'] = $permissions;

            return new JsonResponse($data);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->signOutUser($request);

            return new JsonResponse(['message' => 'User successfully logout']);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
