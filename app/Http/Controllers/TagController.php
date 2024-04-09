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
        $this->authorize('view', $tag);
        return $tag;
    }

    public function store(StoreTagRequest $request)
    {
        $this->authorize('create', Tag::class);

        return
            $request->user()->tags()->create([
                'name' => $request->name,
            ]);

    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        $this->authorize('update', $tag);

        $tag->update($request->validated());

        return $tag;
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();

        return response()->noContent();
    }
}
