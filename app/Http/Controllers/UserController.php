<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return User::all();
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return $user;
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        return User::create($request->validated());
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->validated());

        return $user;
    }

    public function delete(User $user)
    {
        $this->authorize('delete', User::class);

        $user->delete();

        return response()->noContent();
    }
}
