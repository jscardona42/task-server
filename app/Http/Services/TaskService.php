<?php

namespace App\Http\Services;

use App\Models\Task;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class TaskException extends Exception {}

class TaskService
{
    public function getTasks($request)
    {
        try {
            $title = $request->title;
            $userId = $request->header('userid');
            $user = User::with('role')->findOrFail($userId);

            $completed = $request->completed;
            $description = $request->description;
            $userName = $request->userName;
            $sortField = $request->input('order_column', 'id');
            $sortDirection = $request->input('order_direction', 'asc');
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            $statusMap = [
                'COMPLETADA' => 1,
                'NO COMPLETADA' => 0,
            ];

            $tasks = DB::table('tasks')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->where('tasks.status', true)
                ->select(
                    'tasks.id',
                    'tasks.title',
                    'tasks.description',
                    'tasks.completed',
                    'tasks.expiration_date',
                    'users.id as user_id',
                    'users.name as user_name'
                );

            if ($title) {
                $tasks->whereRaw('lower(title) LIKE ?', ['%' . strtolower($title) . '%']);
            }

            if ($description) {
                $tasks->whereRaw('lower(tasks.description) LIKE ?', ['%' . strtolower($description) . '%']);
            }

            if ($userName) {
                $tasks->whereRaw('lower(users.name) LIKE ?', ['%' . strtolower($userName) . '%']);
            }

            if (isset($statusMap[$completed])) {
                $tasks->where('tasks.completed', $statusMap[$completed]);
            }

            if ($user->role->role_name !== "ADMINISTRATOR") {
                $tasks->where('tasks.user_id', $userId);
            }

            $tasks = $tasks
                ->orderBy($sortField, $sortDirection)
                ->paginate($perPage, ['*'], 'page', $page);

            return $tasks;
        } catch (\Exception) {
            throw new TaskException('Error fetching tasks', 500);
        }
    }

    public function getTaskById($id)
    {
        try {
            return Task::with('user')->findOrFail($id);
        } catch (\Exception) {
            throw new TaskException('Error fetching task', 500);
        }
    }

    public function createTask(array $data)
    {
        try {
            Task::create($data);
        } catch (\Exception $e) {
            throw new TaskException('Error creating task', 500);
        }
    }

    public function updateTask($id, array $data)
    {
        try {
            $task = Task::findOrFail($id);
            $task->update($data);
        } catch (\Exception) {
            throw new TaskException('Error updating task', 500);
        }
    }

    public function deleteTask($id)
    {
        try {
            $task = Task::findOrFail($id);
            $task->status = false;
            $task->save();
        } catch (\Exception) {
            throw new TaskException('Error deactivating task', 500);
        }
    }

    public function getTaskByUserId($userId)
    {
        try {
            return Task::with('user')->where('user_id', $userId)->get();
        } catch (\Exception) {
            throw new TaskException('Error fetching tasks for user', 500);
        }
    }
}
