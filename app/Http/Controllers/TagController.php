<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return auth()->user()->tags;
    }

    public function show(Tag $tag)
    {
        $this->checkIfLoginUserAuthorized('view', $tag);
        return $tag;
    }

    public function store(StoreTagRequest $request)
    {
        $this->checkIfLoginUserAuthorized('create', Tag::class);

        return
            $request->user()->tags()->create([
                'name' => $request->name,
            ]);

    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $this->checkIfLoginUserAuthorized('update', $tag);

        $tag->update($request->validated());

        return $tag;
    }

    public function destroy(Tag $tag)
    {
        $this->checkIfLoginUserAuthorized('delete', $tag);

        $tag->delete();

        return response()->noContent();
    }


    private function checkIfLoginUserAuthorized($ability, $model)
    {
        auth()->user()->can($ability, $model) ?: abort(403);
    }

}
