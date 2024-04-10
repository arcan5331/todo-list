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

        $this->initializeCategoryIdIfNotSet($request);

        $task = auth()->user()->tasks()->create($request->only(['title', 'description', 'due_date', 'category_id']));
        if (isset($request->status)) {
            $this->setTaskStatus($task, $request->status);
        }


        $this->initializeTaskTags($task, $request);

        $task->load('tags');
        return $task;
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $this->initializeCategoryIdIfNotSet($request);

        $task->update($request->only(['title', 'description', 'due_date', 'category_id']));
        if (isset($request->status)) {
            $this->setTaskStatus($task, $request->status);
        }

        $this->initializeTaskTags($task, $request);

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

    private function initializeTaskTags(Task $task, Request $request)
    {
        if (isset($request->tags)) {
            $task->syncTags($request->tags);
        }
    }

    private function setCategoryId(Request $request)
    {
        $rootCategory = CategoryController::getUserRootCategory(auth()->user());
        $request->merge(['category_id' => $rootCategory->id]);
    }

    private function initializeCategoryIdIfNotSet(Request $request)
    {
        if (!isset($request->category_id)) {
            $this->setCategoryId($request);
        }
    }
}
