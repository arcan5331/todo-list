<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'nullable',
            'description' => 'nullable',
            'due_date' => 'nullable|date|after_or_equal:today',
            'status' => 'nullable|in:todo,over_due,doing,done',
            'tags' => 'array|nullable',
            'tags.*' => Rule::exists(Tag::class, 'id')
                ->where('user_id', $this->user()->id)
                ->whereNull('deleted_at'),
            'category_id' => [
                'nullable',
                Rule::exists(Category::class, 'id')
                    ->whereNull('deleted_at')
                    ->where('user_id', $this->user()->id)
            ],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
