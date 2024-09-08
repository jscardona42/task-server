<?php

namespace App\Http\Controllers;

use App\Http\Services\TaskService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        try {
            $tasks = $this->taskService->getTasks($request);
            return new JsonResponse($tasks);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $task = $this->taskService->getTaskById($id);
            return new JsonResponse($task);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'expiration_date' => 'required|date',
                'user_id' => 'required|exists:users,id'
            ]);

            $this->taskService->createTask($validatedData);
            return new JsonResponse(['message' => 'Task successfully created']);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'expiration_date' => 'required|date',
                'completed' => 'nullable',
                'user_id' => 'nullable|exists:users,id'
            ]);

            $this->taskService->updateTask($id, $validatedData);
            return new JsonResponse(['message' => 'Task successfully updated']);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $this->taskService->deleteTask($id);
            return new JsonResponse(['message' => 'Task successfully deactived']);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    public function byUser($userId): JsonResponse
    {
        try {
            $tasks =  $this->taskService->getTaskByUserId($userId);
            return new JsonResponse($tasks);
        } catch (Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
