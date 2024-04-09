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
        $this->authorize('view', $task);
        return $task;
    }

    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);
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
        $this->authorize('update', $task);

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
        $this->authorize('delete', $task);
        $task->delete();

        return response()->noContent();
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
