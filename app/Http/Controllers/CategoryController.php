<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return auth()->user()->categories;
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        $this->initializeCategoryIdIfNotSet($request);

        return auth()->user()->categories()->create([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
        ]);
    }

    public function show(Category $category)
    {
        $this->authorize('view', $category);

        return $category;
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update', $category);

        $this->initializeCategoryIdIfNotSet($request);

        $category->update([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
        ]);

        return $category->refresh();
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        if ($category->id === static::getUserRootCategory(auth()->user())->id)
            abort(403);

        $category->delete();

        return response()->json();
    }

    public static function getUserRootCategory(User $user)
    {
        if (!static::checkIfUserHasRootCategory($user))
            static::makeRootCategoryForUser($user);
        return $user->categories()->whereNull('category_id')->sole();
    }

    private static function checkIfUserHasRootCategory(User $user)
    {
        return Category::whereNull('category_id')->where('user_id', $user->id)->exists();
    }

    private static function makeRootCategoryForUser(User $user): void
    {
        $user->categories()->create([
            'name' => 'tag',
        ]);
    }

    private function initializeCategoryIdIfNotSet(Request $request)
    {
        if (!isset($request->category_id)) {
            $rootCategory = $this->getUserRootCategory(auth()->user());
            if ($request->category->id != $rootCategory->id)
                $request->merge([
                    'category_id' => $rootCategory->id,
                ]);
        }
    }

}
