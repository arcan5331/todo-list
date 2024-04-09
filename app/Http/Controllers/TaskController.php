<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        return auth()->user()->tasks;
    }

    public function show(Task $task)
    {
        $this->checkIfLoginUserAuthorized('view', $task);
        return $task;
    }

    public function store(StoreTaskRequest $request)
    {
        $task = auth()->user()->tasks()->create($request->only(['title', 'description', 'due_date']));
        if (isset($request->status)) {
            $this->setTaskStatus($task, $request->status);
        }

        if (isset($request->tags)) {
            $task = $this->syncTaskWithTags($task, $request->tags);
        }

        $task->load('tags');
        return $task;
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->checkIfLoginUserAuthorized('update', $task);

        $task->update($request->only(['title', 'description', 'due_date']));
        if (isset($request->status)) {
            $this->setTaskStatus($task, $request->status);
        }

        if (isset($request->tags)) {
            $task = $this->syncTaskWithTags($task, $request->tags);
        }

        $task->load('tags');
        return $task;
    }

    public function destroy(Task $task)
    {
        $this->checkIfLoginUserAuthorized('delete', $task);
        $task->delete();

        return response()->noContent();
    }


    private function checkIfLoginUserAuthorized($ability, Model $model)
    {
        auth()->user()->can($ability, $model) ?: abort(403);
    }

    private function setTaskStatus(Task $task, $status)
    {
        $status = 'STATUS_' . strtoupper($status);
        return $task->setStatus(constant(Task::class . '::' . $status));
    }

    private function syncTaskWithTags(Task $task, array $tags)
    {
        $task->tags()->sync($tags);
        return $task;
    }

}
